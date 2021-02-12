<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\PromoCode;
use Illuminate\Support\Str;

$factory->define(PromoCode::class, function (Faker $faker) {
    return [
        'code' => Str::random(6),
    ];
});
