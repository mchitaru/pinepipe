<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Contact;
use App\Lead;
use App\LeadStage;
use Faker\Generator as Faker;

$factory->define(Lead::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'price' => $faker->randomNumber(3),
        'stage_id'=> $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*LeadStage::$SEED + 1, 
                                            User::$SEED_COMPANY_IDX*LeadStage::$SEED),
        'user_id'=> $faker->numberBetween(User::$SEED_COMPANY_ID + User::$SEED_CLIENT_COUNT + 1, 
                                            User::$SEED_COMPANY_ID + User::$SEED_CLIENT_COUNT + User::$SEED_STAFF_COUNT),
        'client_id' => $faker->numberBetween(User::$SEED_COMPANY_ID + 1, User::$SEED_COMPANY_ID + User::$SEED_CLIENT_COUNT),
        'source_id' => $faker->numberBetween(1, 4),
        'contact_id' => null,
        'created_by' => User::$SEED_COMPANY_ID,
        'notes' => null
    ];
});

$factory->state(Lead::class, 'contact', function ($faker) {
    return [
        'client_id' => null,
        'contact_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Contact::$SEED + 1, 
                                                User::$SEED_COMPANY_IDX*Contact::$SEED),
    ];
});
