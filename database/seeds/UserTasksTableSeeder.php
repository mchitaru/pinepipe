<?php

use Illuminate\Database\Seeder;

class UserTasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\UserTask::class, App\Task::$PROJECT_SEED)->create();
    }
}
