<?php

use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Lead::class, App\Lead::$SEED)->create();
        // factory(App\Lead::class, 5)->states('contact')->create();
    }
}
