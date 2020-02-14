<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Task;
use App\UserTask;
use Faker\Generator as Faker;
use App\Client;

$factory->define(UserTask::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(User::$SEED_COMPANY_ID + Client::$SEED + 1, 
                                            User::$SEED_COMPANY_ID + Client::$SEED + User::$SEED_STAFF_COUNT), 
        'task_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Task::$SEED_PROJECT + 1, 
                                            User::$SEED_COMPANY_IDX*Task::$SEED_PROJECT)
    ];
});
