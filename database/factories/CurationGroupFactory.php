<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\CurationGroup;
use App\WorkingGroup;
use App\CurationActivity;
use Faker\Generator as Faker;

$factory->define(CurationGroup::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'curation_activity_id' => CurationActivity::select('id')->get()->random()->id,
        'working_group_id' => WorkingGroup::select('id')->get()->random()->id,
        'accepting_volunteers' => $faker->boolean
    ];
});
