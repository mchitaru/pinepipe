<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\TaskReminderJob;

class AppReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for app items';

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
        TaskReminderJob::dispatch();

        $this->info('Reminders sent successfully');
    }
}
