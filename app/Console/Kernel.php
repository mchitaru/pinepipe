<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Jobs\PeriodicSynchronizationsJob;
use App\Jobs\CurrencyRatesJob;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('queue:work --queue=default --tries=1 --timeout=60 --stop-when-empty')->everyMinute();
        $schedule->job(new PeriodicSynchronizationsJob())->everyFifteenMinutes();
        // $schedule->command('event:reminders')->everyMinute()->withoutOverlapping(5);
        $schedule->job(new CurrencyRatesJob())->daily();
        $schedule->command('app:dailyreminders')->daily();

        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
