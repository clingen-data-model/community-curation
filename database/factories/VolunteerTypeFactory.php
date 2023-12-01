<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\VolunteerType;
use Faker\Generator as Faker;

$factory->define(VolunteerType::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
