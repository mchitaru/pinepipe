<?php

use App\PaymentPlan;
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
        PaymentPlan::create(
            [
                'name' => 'Free',
                'price' => 0,
                'duration' => 'unlimited',
                'max_users' => 1,
                'max_clients' => 1,
                'max_projects' => 1,
            ]
        );
        PaymentPlan::create(
            [
                'name' => 'Freelancer',
                'price' => 9,
                'duration' => 'month',
                'max_users' => 1,
                'max_clients' => null,
                'max_projects' => null,
            ]
        );
        PaymentPlan::create(
            [
                'name' => 'Startup',
                'price' => 19,
                'duration' => 'month',
                'max_users' => 10,
                'max_clients' => null,
                'max_projects' => null,
            ]
        );
        PaymentPlan::create(
            [
                'name' => 'Enterprise',
                'price' => 49,
                'duration' => 'month',
                'max_users' => null,
                'max_clients' => null,
                'max_projects' => null,
            ]
        );
    }
}
