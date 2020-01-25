<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserTask;
use Faker\Generator as Faker;

$factory->define(UserTask::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(13, 22), 
        'task_id' => $faker->numberBetween(1, 30)
    ];
});
