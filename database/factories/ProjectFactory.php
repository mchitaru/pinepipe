<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'price' => $faker->randomNumber(3),
        'start_date' => $faker->date(),
        'due_date' => $faker->date(),
        'client' => '3',
        'description' => $faker->text,
        'label' => $faker->randomNumber(5),
        'status' => 'on_going',
        'created_by' => '2',
    ];
});
