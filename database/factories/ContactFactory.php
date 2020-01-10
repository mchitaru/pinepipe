<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Contacts;
use Faker\Generator as Faker;

$factory->define(Contacts::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'company' => $faker->company,
        'job' => $faker->jobTitle,
        'website' => 'https:\\www.basecrm.io',
        'birthday' => $faker->date,
        'notes' => $faker->text
    ];
});
