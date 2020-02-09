<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Project;
use App\User;
use App\Invoice;
use Faker\Generator as Faker;

$factory->define(Invoice::class, function (Faker $faker) {
    return [
        'invoice_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Invoice::$SEED + 1, 
                                                User::$SEED_COMPANY_IDX*Invoice::$SEED),
        'project_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Project::$SEED + 1, 
                                                User::$SEED_COMPANY_IDX*Project::$SEED),
        'status' => 0,
        'issue_date' => $faker->date(),
        'due_date' => $faker->date(),
        'discount' => '0',
        'tax_id' => '1',
        'terms' => '',
        'created_by'=> User::$SEED_COMPANY_ID,
    ];
});
