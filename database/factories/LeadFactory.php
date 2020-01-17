<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Lead;
use Faker\Generator as Faker;

$factory->define(Lead::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'price' => $faker->randomNumber(3),
        'stage'=> '1',
        'owner'=> '4',
        'client' => '3',
        'source' => '1',
        'created_by' => '2',
        'notes' => $faker->text
    ];
});
