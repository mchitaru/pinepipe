<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserProject;
use Faker\Generator as Faker;

$factory->define(UserProject::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(2, 30), 
        'project_id' => $faker->numberBetween(1, 20)
    ];
});
