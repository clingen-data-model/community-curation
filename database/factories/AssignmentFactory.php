<?php

namespace Database\Factories;

use App\Assignment;
use App\CurationGroup;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class AssignmentFactory extends Factory
{
    use WithFaker;
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

        return [
            'user_id' => $user->id,
            'assignable_type' => get_class($panel),
            'assignable_id' => $panel->id,
        ];
    }
}
