<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Preference;
use Faker\Generator as Faker;

$factory->define(Preference::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word.'-'.uniqid(),
        'data_type' => 'boolean',
        'description' => $faker->sentence,
        'applies_to_volunteer' => true,
        'applies_to_user' => true,
    ];
});
