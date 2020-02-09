<?php

use Illuminate\Database\Seeder;
use App\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Task::class, Task::$SEED_PROJECT)->states('project')->create();
        factory(Task::class, Task::$SEED_FREE)->create();
    }
}
