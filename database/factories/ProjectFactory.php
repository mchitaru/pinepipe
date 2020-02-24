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
        // 'start_date' => $faker->dateTimeInInterval('-2 months'),
        // 'due_date' => $faker->dateTimeInInterval('-1 month', '+ 6 months'),
        'client_id' => $faker->numberBetween(1, Client::$SEED),
        'description' => $faker->text,
        'archived' => false,
        'created_by' => User::$SEED_COMPANY_ID,
    ];
});
