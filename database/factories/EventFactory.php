<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Event;
use Faker\Generator as Faker;
use Carbon\Carbon;
use App\Client;

$factory->define(Event::class, function (Faker $faker) {

    $date = $faker->dateTimeInInterval('-3 day','+3 day');

    return [
        'active' => true,
        'name' => $faker->company.' event',
        'start' => $date,
        'end' => Carbon::parse($date)->addHours($faker->numberBetween(1, 24)),
        'busy' => true,
        'notes' => null,
        'user_id' => $faker->numberBetween(User::$SEED_COMPANY_ID + Client::$SEED + 1, 
                                            User::$SEED_COMPANY_ID + Client::$SEED + User::$SEED_STAFF_COUNT),
        'created_by' => User::$SEED_COMPANY_ID,
    ];
});
