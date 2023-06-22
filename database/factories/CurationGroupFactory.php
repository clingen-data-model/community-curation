<?php

namespace Database\Factories;

use App\CurationActivity;
use App\CurationGroup;
use App\WorkingGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class CurationGroupFactory extends Factory
{
    use WithFaker;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CurationGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'curation_activity_id' => CurationActivity::select('id')->get()->random()->id,
            'working_group_id' => WorkingGroup::select('id')->get()->random()->id,
            'accepting_volunteers' => $this->faker->boolean(),
        ];
    }
}
