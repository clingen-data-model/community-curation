<?php

namespace Database\Factories;

use App\CurationGroup;
use App\CustomSurvey;
use App\VolunteerType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomSurveyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomSurvey::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'curation_group_id' => CurationGroup::factory()->create()->id,
            'volunteer_type_id' => factory(VolunteerType::class)->create()->id,
            'name' => uniqid(),
        ];
    }
}
