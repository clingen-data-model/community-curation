<?php

namespace Tests\Feature\End2End\VolunteerSurveys;

use App\CustomSurvey;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group custom-surveys
 */
class TakeCustomSurveyTest extends TestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
        $this->cs = CustomSurvey::factory()->create();
    }

    /**
     * @test
     */
    public function returns_404_if_custom_survey_with_name_not_found(): void
    {
        $this->call('GET', '/apply/group/beans')
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function returns_200_if_custom_survey_with_name_found(): void
    {
        $this->call('GET', '/apply/group/'.$this->cs->name)
            ->assertOk();
    }

    /**
     * @test
     */
    public function sets_custom_survey_id_in_hidden_field(): void
    {
        $this->call('GET', '/apply/group/'.$this->cs->name)
            ->assertSee('<input type="hidden" name="custom_survey_id" value="'.$this->cs->id.'">', false);
    }

    /**
     * @test
     */
    public function stores_custom_survey_data_to_application_response(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->call(
            'POST',
            '/apply/group/'.$this->cs->name,
            [
                'custom_survey_id' => $this->cs->id,
                'nav' => 'next',
            ]
        );

        $this->assertDatabaseHas('rsp_application_1', [
            'custom_survey_id' => $this->cs->id,
            'volunteer_type' => $this->cs->volunteer_type_id,
            'panel_1' => $this->cs->curation_group_id,
            'curation_activity_1' => $this->cs->curationGroup->curationActivity->id,
        ]);
    }
}
