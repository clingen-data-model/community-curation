<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Motivation;
use Faker\Generator as Faker;

$factory->define(Motivation::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word(),
    ];
});
