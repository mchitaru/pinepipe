<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'slug' => Str::of($faker->sentence)->slug('-'),
        'content' => $faker->text,
        'published' => true,
        'user_id' => 0,
        'created_by' => 0,
    ];
});
