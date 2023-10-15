<?php

namespace Database\Factories;

use App\CurationGroup;
use App\VolunteerType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomSurveyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'curation_group_id' => CurationGroup::factory()->create()->id,
            'volunteer_type_id' => factory(VolunteerType::class)->create()->id,
            'name' => uniqid(),
        ];
    }
}
