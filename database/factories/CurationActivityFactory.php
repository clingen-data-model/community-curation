<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\CurationActivity;
use Faker\Generator as Faker;

$factory->define(CurationActivity::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word
    ];
});
