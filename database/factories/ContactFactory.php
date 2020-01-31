<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'client_id' => $faker->numberBetween(3, 4),
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'company' => null,
        'job' => $faker->jobTitle,
        'website' => 'https:\\www.basecrm.io',
        'birthday' => $faker->date,
        'notes' => $faker->text,
        'created_by' => '2'
    ];
});
