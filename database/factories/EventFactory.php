<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Event::class, function (Faker $faker) {

    $date = $faker->dateTimeInInterval('-3 day','+3 day');

    return [
        'active' => true,
        'name' => $faker->sentence(3),
        'category_id' => $faker->numberBetween(1, 6),
        'start' => $date,
        'end' => Carbon::parse($date)->addHours($faker->numberBetween(1, 24)),
        'busy' => true,
        'notes' => null,
        'user_id' => $faker->numberBetween(5, 9),
        'created_by' => '2',
    ];
});
