<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\VolunteerStatus;
use Faker\Generator as Faker;

$factory->define(VolunteerStatus::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word(),
    ];
});
