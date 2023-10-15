<?php

namespace Tests\Feature;

use App\Assignment;
use App\CurationGroup;
use App\CustomSurvey;
use App\Priority;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CurationGroupDeleteTest extends TestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
        $this->curationGroup = CurationGroup::factory()->create();
    }

    /**
     * @test
     */
    public function deletes_all_priorities_for_curation_group(): void
    {
        $priority = Priority::factory()->create([
            'curation_group_id' => $this->curationGroup->id,
        ]);

        $this->curationGroup->delete();

        $this->assertModelMissing('priorities', [
            'id' => $priority->id,
        ]);
    }

    /**
     * @test
     */
    public function deletes_all_assignments_to_curation_group(): void
    {
        $assignment = Assignment::factory()->create([
            'assignable_type' => CurationGroup::class,
            'assignable_id' => $this->curationGroup->id,
        ]);

        $this->curationGroup->delete();

        $this->assertSoftDeleted('assignments', [
            'id' => $assignment->id,
        ]);
    }

    /**
     * @test
     */
    public function deletes_all_custom_survecys_for_curation_group(): void
    {
        $customSurvey = CustomSurvey::factory()->create([
            'curation_group_id' => $this->curationGroup->id,
        ]);

        $this->curationGroup->delete();

        $this->assertSoftDeleted('custom_surveys', [
            'id' => $customSurvey->id,
        ]);
    }
}
