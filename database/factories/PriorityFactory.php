<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\User;
use App\Priority;
use App\ExpertPanel;
use Faker\Generator as Faker;

$panels = ExpertPanel::all()->groupBy('curation_activity_id');

$factory->define(Priority::class, function (Faker $faker) use ($panels) {
    $user = User::all()->random();
    if (!$user) {
        $user = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
    }
    $activityId = $faker->numberBetween(1, 5);
    $panels = $panels->where('curation_activity_id', $activityId);
    $panel = $panels->first();
    if (!$panel) {
        $panel = factory(ExpertPanel::class)->create([
            'curation_activity_id' => $activityId
        ]);
    }
    $panelId = $panel->id;
    return [
        'priority_order' => $faker->numberBetween(1,3),
        'user_id' => $user->id,
        'curation_activity_id' => $activityId,
        'expert_panel_id' => $panelId,
        'activity_experience' => $faker->boolean,
        'activity_experience_details' => $faker->sentence,
        'effort_experience' => $faker->boolean,
        'effort_experience_details' => $faker->sentence,
        'prioritization_round' => 1
     ];
});
