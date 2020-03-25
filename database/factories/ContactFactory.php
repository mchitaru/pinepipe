<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Client;
use App\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'client_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Client::$SEED + 1,
                                            User::$SEED_COMPANY_IDX*Client::$SEED),
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'company' => null,
        'job' => $faker->jobTitle,
        'website' => 'https:\\www.pinepipe.com',
        'birthday' => $faker->date,
        'notes' => $faker->text,
        'created_by' => User::$SEED_COMPANY_ID
    ];
});
