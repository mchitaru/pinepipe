<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'client_id' => $faker->numberBetween(User::$SEED_COMPANY_ID + 1, User::$SEED_COMPANY_ID + User::$SEED_CLIENT_COUNT),
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'company' => null,
        'job' => $faker->jobTitle,
        'website' => 'https:\\www.basecrm.io',
        'birthday' => $faker->date,
        'notes' => $faker->text,
        'created_by' => User::$SEED_COMPANY_ID
    ];
});
