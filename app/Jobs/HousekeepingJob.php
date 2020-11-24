<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;
use App\User;
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
        $this->cleanupAccounts();

        $this->initDefaultData();
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

    public function cleanupAccounts()
    {
        $users = User::withoutGlobalScopes()
                                ->where('email_verified_at', null)
                                ->where('handle', null)
                                ->where('created_at', '<', Carbon::now()->subMonths(1))
                                ->get();

        $users->each(function($user) {
            $user->delete();
        });
    }

    public function initDefaultData()
    {
        $users = User::withoutGlobalScopes()
                        ->where('handle', '!=', null)
                        ->get();

        $users->each(function($user) {
            $user->initCompanyDefaults();
        });
    }

}
