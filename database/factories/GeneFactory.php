<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Gene;
use Faker\Generator as Faker;

$factory->define(Gene::class, function (Faker $faker) {
    return [
        'symbol' => $faker->randomLetter().$faker->randomLetter().$faker->randomLetter().$faker->randomLetter(),
        'protocol_path' => null,
        'hypothesis_group' => null,
    ];
});
