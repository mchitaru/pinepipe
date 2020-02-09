<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Task;
use App\UserTask;
use Faker\Generator as Faker;

$factory->define(UserTask::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(User::$SEED_COMPANY_ID + User::$SEED_CLIENT_COUNT + 1, 
                                            User::$SEED_COMPANY_ID + User::$SEED_CLIENT_COUNT + User::$SEED_STAFF_COUNT), 
        'task_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Task::$SEED_PROJECT + 1, 
                                            User::$SEED_COMPANY_IDX*Task::$SEED_PROJECT)
    ];
});
