<?php

namespace Tests\Feature;

use App\Priority;
use App\Assignment;
use Tests\TestCase;
use App\CustomSurvey;
use App\CurationGroup;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CurationGroupDeleteTest extends TestCase
{
    use DatabaseTransactions;

    private $curationGroup;

    public function setup():void
    {
        parent::setup();
        $this->curationGroup = CurationGroup::factory()->create();
    }

    /**
     * @test
     */
    public function deletes_all_priorities_for_curation_group()
    {
        $priority = Priority::factory()->create([
            'curation_group_id' => $this->curationGroup->id
        ]);

        $this->curationGroup->delete();

        $this->assertNull(Priority::find($priority->id));
    }
    
    /**
     * @test
     */
    public function deletes_all_assignments_to_curation_group()
    {
        $assignment = Assignment::factory()->create([
            'assignable_type' => CurationGroup::class,
            'assignable_id' => $this->curationGroup->id
        ]);

        $this->curationGroup->delete();

        $this->assertSoftDeleted('assignments', [
            'id' => $assignment->id
        ]);
    }

    /**
     * @test
     */
    public function deletes_all_custom_survecys_for_curation_group()
    {
        $customSurvey = CustomSurvey::factory()->create([
            'curation_group_id' => $this->curationGroup->id
        ]);

        $this->curationGroup->delete();

        $this->assertSoftDeleted('custom_surveys', [
            'id' => $customSurvey->id
        ]);
    }
    
    
}
