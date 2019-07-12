<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\ExpertPanel;
use Faker\Generator as Faker;
use App\CurationActivity;

$factory->define(ExpertPanel::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'curation_activity_id' => CurationActivity::select('id')->get()->random()->id
    ];
});
