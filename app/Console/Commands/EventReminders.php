<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\EventReminderJob;

class EventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:remainders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        EventReminderJob::dispatch();

        $this->info('Reminders sent successfully');
    }
}
