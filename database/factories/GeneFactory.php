<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Gene;
use Faker\Generator as Faker;

$factory->define(Gene::class, function (Faker $faker) {
    return [
        'symbol' => $faker->randomLetter().$faker->randomLetter().$faker->randomLetter().$faker->randomLetter(),
        'hgnc_id' => 'HGNC:'.$faker->numberBetween(10000, 99999),
        'protocol_path' => null,
        'hypothesis_group' => null
    ];
});
