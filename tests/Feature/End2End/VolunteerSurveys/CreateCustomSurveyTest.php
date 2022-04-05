<?php

namespace Tests\Feature\End2End\VolunteerSurveys;

use App\User;
use Tests\TestCase;
use App\CustomSurvey;
use App\CurationGroup;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group custom-surveys
 */
class CreateCustomSurveyTest extends TestCase
{
    use DatabaseTransactions;

    public function setup():void
    {
        parent::setup();
        $this->superAdmin = factory(User::class)->state('super-admin')->create();
        $this->curationGroup = CurationGroup::factory()->create();
    }

    /**
     * @test
     */
    public function superadmin_can_create_custom_volunteer_survey()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->superAdmin, 'api')
            ->json('POST', '/admin/custom-survey', [
                'curation_group_id' => $this->curationGroup->id,
                'volunteer_type_id' => 2,
                'name' => $this->curationGroup->name
            ]);

        $this->assertDatabaseHas('custom_surveys', [
            'curation_group_id' => $this->curationGroup->id,
            'volunteer_type_id' => 2,
            'name' => Str::kebab($this->curationGroup->name)
        ]);
    }

    /**
     * @test
     */
    public function validates_required_attributes()
    {
        $response = $this->actingAs($this->superAdmin, 'api')
            ->json('POST', '/admin/custom-survey', [
                'curation_group_id' => null,
                'volunteer_type_id' => null,
                'name' => null
            ]);
        $response->assertStatus(422);

        $response->assertJsonFragment(['The curation group id field is required.']);
        $response->assertJsonFragment(['The volunteer type id field is required.']);
        $response->assertJsonFragment(['The name field is required.']);
    }

    /**
     * @test
     */
    public function validates_present_data()
    {
        $cs = CustomSurvey::factory()->create();

        $response = $this->actingAs($this->superAdmin, 'api')
            ->json('POST', '/admin/custom-survey', [
                'curation_group_id' => 99999,
                'volunteer_type_id' => 99999,
                'name' => $cs->name
            ]);
        $response->assertStatus(422);

        $response->assertJsonFragment(['The selected curation group id is invalid.']);
        $response->assertJsonFragment(['The selected volunteer type id is invalid.']);
        $response->assertJsonFragment(['The name has already been taken.']);
    }

    /**
     * @test
     */
    public function validates_name_does_not_contain_url_special_characters()
    {
        $cs = CustomSurvey::factory()->create();

        $response = $this->actingAs($this->superAdmin, 'api')
            ->json('POST', '/admin/custom-survey', [
                'name' => 'test/me'
            ]);
        $response->assertStatus(422);

        $response->assertJsonFragment(['The custom survey name may not include any of the following characters: [ ] { } | \ ” % ~ # < >']);
    }
    
}
