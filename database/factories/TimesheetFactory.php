<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Project;
use App\User;
use App\Task;
use App\Timesheet;
use Faker\Generator as Faker;
use App\Client;

$factory->define(Timesheet::class, function (Faker $faker) {
    return [
        'project_id' => null,
        'user_id'=> $faker->numberBetween(User::$SEED_COMPANY_ID + Client::$SEED + 1, 
                                            User::$SEED_COMPANY_ID + Client::$SEED + User::$SEED_STAFF_COUNT),
        'task_id' => null,
        'date' => $faker->date(),
        'hours' => $faker->randomNumber(2),
        'remark' => null,
        'created_by' => User::$SEED_COMPANY_ID,
    ];
});
