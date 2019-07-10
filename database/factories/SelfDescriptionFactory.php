<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\SelfDescription;
use Faker\Generator as Faker;

$factory->define(SelfDescription::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word
    ];
});
