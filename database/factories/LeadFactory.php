<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Lead;
use Faker\Generator as Faker;

$factory->define(Lead::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'price' => $faker->randomNumber(3),
        'stage'=> $faker->numberBetween(1, 4),
        'owner'=> $faker->numberBetween(13, 22),
        'client' => $faker->numberBetween(3, 12),
        'source' => $faker->numberBetween(1, 4),
        'created_by' => $faker->numberBetween(13, 22),
        'notes' => $faker->text
    ];
});
