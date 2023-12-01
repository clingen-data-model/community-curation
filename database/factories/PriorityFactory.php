<?php

namespace Database\Factories;

use App\CurationGroup;
use App\Priority;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class PriorityFactory extends Factory
{
    use WithFaker;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Priority::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $panels = CurationGroup::all()->groupBy('curation_activity_id');

        $user = User::all()->random();
        if (! $user) {
            $user = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        }
        $activityId = $this->faker->numberBetween(1, 5);
        $panels = $panels->where('curation_activity_id', $activityId);
        $panel = $panels->first();
        if (! $panel) {
            $panel = CurationGroup::factory()->create([
                'curation_activity_id' => $activityId,
            ]);
        }
        $panelId = $panel->id;

        return [
            'priority_order' => $this->faker->numberBetween(1, 3),
            'user_id' => $user->id,
            'curation_activity_id' => $activityId,
            'curation_group_id' => $panelId,
            'activity_experience' => $this->faker->boolean,
            'activity_experience_details' => $this->faker->sentence,
            'effort_experience' => $this->faker->boolean,
            'effort_experience_details' => $this->faker->sentence,
            'prioritization_round' => 1,
        ];
    }
}
