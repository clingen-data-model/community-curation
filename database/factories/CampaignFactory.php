<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Campaign;
use Faker\Generator as Faker;

$factory->define(Campaign::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word
    ];
});
