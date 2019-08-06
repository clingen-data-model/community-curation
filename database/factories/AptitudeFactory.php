<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Aptitude;
use App\VolunteerType;
use Faker\Generator as Faker;

$factory->define(Aptitude::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'curation_activity_id' => CurationActivity::select('id')->get()->random()->id,
        'volunteer_type_id' => VolunteerType::select('id')->get()->random()->id,
    ];
});
