<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tax;
use Faker\Generator as Faker;

$factory->define(Tax::class, function (Faker $faker) {
    return [
        'name' => 'VAT', 
        'rate' => 19.00, 
        'created_by' => 2
    ];
});
