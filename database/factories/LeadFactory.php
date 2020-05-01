<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Contact;
use App\Lead;
use App\Stage;
use Faker\Generator as Faker;
use App\Client;

$factory->define(Lead::class, function (Faker $faker) {
    return [
        'name' => $faker->company.' lead',
        'price' => $faker->randomNumber(4),
        'stage_id'=> $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Stage::$LEAD_SEED + 1,
                                            User::$SEED_COMPANY_IDX*Stage::$LEAD_SEED),
        'user_id'=> $faker->numberBetween(User::$SEED_COMPANY_ID + Client::$SEED + 1,
                                            User::$SEED_COMPANY_ID + Client::$SEED + User::$SEED_STAFF_COUNT),
        'client_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Client::$SEED + 1,
                                                User::$SEED_COMPANY_IDX*Client::$SEED),
        'contact_id' => null,
        'created_by' => User::$SEED_COMPANY_ID
    ];
});

$factory->state(Lead::class, 'contact', function ($faker) {
    return [
        'client_id' => null,
        'contact_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Contact::$SEED + 1,
                                                User::$SEED_COMPANY_IDX*Contact::$SEED),
    ];
});
