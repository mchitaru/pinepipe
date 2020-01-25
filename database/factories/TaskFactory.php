<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(3),
        'priority' => $faker->randomElement(['low', 'medium', 'high']),
        'description' => $faker->text,
        'start_date' => $faker->dateTimeInInterval('-2 months'),
        'due_date'  => $faker->dateTimeInInterval('-1 month', '+ 6 months'),
        'assign_to' => $faker->numberBetween(13, 22),
        'project_id' => null,
        'milestone_id' => null,
        'status' => 'todo',
        'order' => '0',
        'stage' => $faker->numberBetween(1, 4),
        'created_by' => '2',
    ];
});

$factory->state(Task::class, 'project', function ($faker) {
    return [
        'project_id' => $faker->numberBetween(0, 10),
    ];
});