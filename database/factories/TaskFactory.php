<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Task;
use App\Project;
use App\ProjectStage;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'title' => $faker->company.' task',
        'priority' => $faker->randomElement(['low', 'medium', 'high']),
        'description' => $faker->text,
        'due_date'  => $faker->dateTimeInInterval('-1 month', '+ 6 months')->format('Y-m-d'),
        'project_id' => null,
        'milestone_id' => null,
        'order' => 0,
        'stage_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*ProjectStage::$SEED + 1, 
                                                User::$SEED_COMPANY_IDX*ProjectStage::$SEED),
        'created_by' => User::$SEED_COMPANY_ID,
    ];
});

$factory->state(Task::class, 'project', function ($faker) {
    return [
        'project_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Project::$SEED + 1, 
                                                User::$SEED_COMPANY_IDX*Project::$SEED),
    ];
});