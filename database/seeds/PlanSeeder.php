<?php

use App\SubscriptionPlan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubscriptionPlan::create(
            [
                'name' => 'Free',
                'paddle_id' => 'free',
                'price' => 0,
                'duration' => null,
                'max_users' => 0,
                'max_clients' => 2,
                'max_projects' => 2,
                'max_space' => null
            ]
        );
        SubscriptionPlan::create(
            [
                'name' => 'Freelancer',
                'paddle_id' => '587387',
                'price' => 9,
                'duration' => 1,
                'max_users' => 0,
                'max_clients' => null,
                'max_projects' => null,
                'max_space' => null
            ]
        );
        SubscriptionPlan::create(
            [
                'name' => 'Startup',
                'paddle_id' => '587388',
                'price' => 19,
                'duration' => 1,
                'max_users' => 5,
                'max_clients' => null,
                'max_projects' => null,
                'max_space' => null
            ]
        );
        SubscriptionPlan::create(
            [
                'name' => 'Enterprise',
                'paddle_id' => '587389',
                'price' => 49,
                'duration' => 1,
                'max_users' => 30,
                'max_clients' => null,
                'max_projects' => null,
                'max_space' => null
            ]
        );
    }
}
