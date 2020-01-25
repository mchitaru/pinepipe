<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Lead;
use Faker\Generator as Faker;

$factory->define(Lead::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'price' => $faker->randomNumber(3),
        'stage_id'=> $faker->numberBetween(1, 4),
        'user_id'=> $faker->numberBetween(13, 22),
        'client_id' => $faker->numberBetween(3, 12),
        'source_id' => $faker->numberBetween(1, 4),
        'created_by' => $faker->numberBetween(13, 22),
        'notes' => $faker->text
    ];
});
