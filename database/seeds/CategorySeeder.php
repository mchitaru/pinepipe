<?php

use Illuminate\Database\Seeder;

use App\Category;
use App\Event;
use App\Expense;
use App\Payment;
use App\Lead;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // LeadSource
        $leads = [
            'Email',
            'Facebook',
            'Google',
            'Phone',
        ];
        foreach($leads as $key => $lead)
        {
            Category::create(
                [
                    'name' => $lead,
                    'class' => Lead::class,
                    'order' => $key,
                    'user_id' => 0,
                    'created_by' => 0,
                ]
            );
        }

        // Expense Category
        $expenses = [
            'Snack',
            'Server Charge',
            'Bills',
            'Office',
            'Assests',
        ];
        foreach($expenses as $key => $expense)
        {
            Category::create(
                [
                    'name' => $expense,
                    'class' => Expense::class,
                    'order' => $key,
                    'user_id' => 0,
                    'created_by' => 0,
                ]
            );
        }

        // Payments
        $payments = [
            'Cash',
            'Bank',
        ];
        foreach($payments as $key => $payment)
        {
            Category::create(
                [
                    'name' => $payment,
                    'class' => Payment::class,
                    'order' => $key,
                    'user_id' => 0,
                    'created_by' => 0,
                ]
            );
        }
    }
}
