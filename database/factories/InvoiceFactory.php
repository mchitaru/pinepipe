<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Invoice;
use Faker\Generator as Faker;

$factory->define(Invoice::class, function (Faker $faker) {
    return [
        'invoice_id' => $faker->numberBetween(1, 9),
        'project_id' => $faker->numberBetween(1, 2),
        'status' => 0,
        'issue_date' => $faker->date(),
        'due_date' => $faker->date(),
        'discount' => '0',
        'tax_id' => '1',
        'terms' => '',
        'created_by'=> 2,
    ];
});
