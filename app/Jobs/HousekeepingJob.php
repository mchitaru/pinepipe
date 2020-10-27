<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;
use App\Activity;

class HousekeepingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->cleanupActivities();
    }

    public function cleanupActivities()
    {
        $activities = Activity::withoutGlobalScopes()
                                ->where('created_at', '<', Carbon::now()->subMonths(12))
                                ->get();

        $activities->each(function($activity) {
            $activity->delete();
        });
    }
}
