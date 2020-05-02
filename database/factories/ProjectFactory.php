<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Project;
use Faker\Generator as Faker;
use App\Client;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->company.' project',
        'price' => $faker->randomNumber(3),
        'start_date' => $faker->dateTimeInInterval('-1 month', '+ 1 months')->format('Y-m-d'),
        'due_date' => $faker->dateTimeInInterval('today', '+ 1 months')->format('Y-m-d'),
        'client_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Client::$SEED + 1,
                                            User::$SEED_COMPANY_IDX*Client::$SEED),
        'description' => $faker->text,
        'archived' => false,
        'user_id' => User::$SEED_COMPANY_ID,
        'created_by' => User::$SEED_COMPANY_ID,
    ];
});
