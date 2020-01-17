<?php

use App\PaymentPlan;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentPlan::create(
            [
                'name' => 'Free PaymentPlan',
                'price' => 0,
                'duration' => 'Unlimited',
                'max_users' => 5,
                'max_clients' => 5,
                'max_projects' => 5,
                'image'=>'free_plan.png',
            ]
        );
    }
}
