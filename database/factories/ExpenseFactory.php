<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use App\User;
use App\Expense;
use Faker\Generator as Faker;
use App\Client;

$factory->define(Expense::class, function (Faker $faker) {
    return [
        'amount' => $faker->randomNumber(3),
        'date' => $faker->date(),
        'project_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Project::$SEED + 1, 
                        User::$SEED_COMPANY_IDX*Project::$SEED),
        'user_id' => $faker->numberBetween(User::$SEED_COMPANY_ID + Client::$SEED + 1, 
                        User::$SEED_COMPANY_ID + Client::$SEED + User::$SEED_STAFF_COUNT), 
        'description' => null,
        'attachment' => null,
        'created_by' => User::$SEED_COMPANY_ID,
    ];
});
