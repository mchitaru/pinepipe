<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\UserCompany;

$factory->define(UserCompany::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 100), 
        'company_id' => $faker->numberBetween(1, 100)
    ];
});
