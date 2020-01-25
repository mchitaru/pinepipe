<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PlansTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(LeadsTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(UserProjectsTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(UserTasksTableSeeder::class);
     }
}
