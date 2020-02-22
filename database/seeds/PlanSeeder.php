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
                'braintree_id' => 'free',
                'price' => 0,
                'duration' => null,
                'max_users' => 1,
                'max_clients' => 1,
                'max_projects' => 1,
                'max_space' => null
            ]
        );
        PaymentPlan::create(
            [
                'name' => 'Freelancer',
                'braintree_id' => 'freelancer',
                'price' => 9,
                'duration' => 1,
                'max_users' => 1,
                'max_clients' => null,
                'max_projects' => null,
                'max_space' => null
            ]
        );
        PaymentPlan::create(
            [
                'name' => 'Startup',
                'braintree_id' => 'startup',
                'price' => 19,
                'duration' => 1,
                'max_users' => 10,
                'max_clients' => null,
                'max_projects' => null,
                'max_space' => null
            ]
        );
        PaymentPlan::create(
            [
                'name' => 'Enterprise',
                'braintree_id' => 'enterprise',
                'price' => 49,
                'duration' => 1,
                'max_users' => null,
                'max_clients' => null,
                'max_projects' => null,
                'max_space' => null
            ]
        );
    }
}
