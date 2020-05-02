<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Tax;
use Faker\Generator as Faker;

$factory->define(Tax::class, function (Faker $faker) {
    return [
        'name' => 'VAT', 
        'rate' => 19.00, 
        'user_id' => User::$SEED_COMPANY_ID,
        'created_by' => User::$SEED_COMPANY_ID
    ];
});
