<?php

namespace Database\Factories;

use App\CurationActivity;
use App\WorkingGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class CurationGroupFactory extends Factory
{
    use WithFaker;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'curation_activity_id' => CurationActivity::select('id')->get()->random()->id,
            'working_group_id' => WorkingGroup::select('id')->get()->random()->id,
            'accepting_volunteers' => $this->faker->boolean(),
        ];
    }
}
