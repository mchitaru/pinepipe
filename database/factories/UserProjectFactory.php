<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Project;
use App\UserProject;
use Faker\Generator as Faker;
use App\Client;

$factory->define(UserProject::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(User::$SEED_COMPANY_ID + Client::$SEED + 1, 
                                            User::$SEED_COMPANY_ID + Client::$SEED + User::$SEED_STAFF_COUNT), 
        'project_id' => $faker->numberBetween((User::$SEED_COMPANY_IDX-1)*Project::$SEED + 1, 
                                                User::$SEED_COMPANY_IDX*Project::$SEED)
    ];
});
