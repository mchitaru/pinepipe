<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserProject;
use Faker\Generator as Faker;

$factory->define(UserProject::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(5, 9), 
        'project_id' => $faker->numberBetween(1, 2)
    ];
});
