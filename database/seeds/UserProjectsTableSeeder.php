<?php

use Illuminate\Database\Seeder;

class UserProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\UserProject::class, 5)->create();
    }
}
