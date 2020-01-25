<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'price' => $faker->randomNumber(3),
        'start_date' => $faker->dateTimeInInterval('-2 months'),
        'due_date' => $faker->dateTimeInInterval('-1 month', '+ 6 months'),
        'client_id' => $faker->numberBetween(3, 12),
        'description' => $faker->text,
        'status' => 'on_going',
        'created_by' => '2',
    ];
});
