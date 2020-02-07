<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\EventAlert;
use App\User;

class EventReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->users = User::with(['events' => function ($query) {
            $query->where('events.end', '>', date('Y-m-d H:i'))
            ->where('events.start', '<', date('Y-m-d H:i', time()+900))
            ->pluck('events.name', 'events.id');
        }])
        ->where('type', '!=', 'super admin')
        ->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->users as $user)
        {
            if(!$user->events->isEmpty())
            {
                $user->notify(new EventAlert($user->events));
            }
        }        
    }
}
