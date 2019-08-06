<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Aptitude;
use App\VolunteerType;
use Faker\Generator as Faker;

$factory->define(Aptitude::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'volunteer_type_id' => VolunteerType::select('id')->get()->random()->id
    ];
});
