<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\HousekeepingJob;

class Housekeeping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:housekeeping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform housekeeping tasks';

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
        HousekeepingJob::dispatch();

        $this->info('Housekeeping done successfully');
    }
}
