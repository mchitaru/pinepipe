<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Services\Google;

class SynchronizeGoogleEvents extends SynchronizeGoogleResource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function getGoogleService()
    {
        return app(Google::class)
            // We access the token through the `googleAccount` relationship.
            ->connectUsing($this->synchronizable->googleAccount->token)
            ->service('Calendar');
    }

    public function getGoogleRequest($service, $options)
    {
        return $service->events->listEvents(
            // We provide the Google ID of the calendar from which we want the events.
            $this->synchronizable->google_id, $options
        );
    }

    public function syncItem($googleEvent)
    {
        // A Google event has been deleted if its status is `cancelled`.
        if ($googleEvent->status === 'cancelled' ||
            $this->parseDatetime($googleEvent->end) < now()) {

            return $this->synchronizable->events()
                ->where('google_id', $googleEvent->id)
                ->delete();
        }

        $event = $this->synchronizable->events()->updateOrCreate(
            [
                'google_id' => $googleEvent->id,
            ],
            [
                'name' => $googleEvent->summary,
                'description' => $googleEvent->description,
                'allday' => $this->isAllDayEvent($googleEvent), 
                'start' => $this->parseDatetime($googleEvent->start), 
                'end' => $this->parseDatetime($googleEvent->end), 
                'user_id' => $this->synchronizable->user_id,
                'created_by' => $this->synchronizable->created_by,                
            ]
        );

        $users = collect($this->synchronizable->user_id);
        $event->users()->sync($users);
    }

    protected function isAllDayEvent($googleEvent)
    {
        return ! $googleEvent->start->dateTime && ! $googleEvent->end->dateTime;
    }

    protected function parseDatetime($googleDatetime)
    {
        $rawDatetime = $googleDatetime->dateTime ?: $googleDatetime->date;

        return Carbon::parse($rawDatetime)->setTimezone('UTC');
    }

    public function dropAllSyncedItems()    
    {   
        $this->synchronizable->events()->delete();  
    }    
}
