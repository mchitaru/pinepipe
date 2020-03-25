<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Client;
use App\User;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'website' => 'https:\\www.pinepipe.com',
        'created_by' => User::$SEED_COMPANY_ID
    ];
});
