<?php

namespace Tests\Feature\Integration\Volunteers\Baseline;

use App\Aptitude;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DefaultAptitudeAssignmentTest extends TestCase
{
    public function setup():void
    {
        parent::setup();
        $this->user = factory(User::class)->states(['volunteer', 'baseline'])->create();
    }

    /**
     * @test
     */
    public function volunteer_assigned_genetic_evidence_aptitude_when_assigned_to_baseline_ca()
    {
        $geneticEvidenceAptitude = Aptitude::find(config('aptitudes.baseline-genetic-evidence'));
        $this->assertUserAssignedTo($this->user, $geneticEvidenceAptitude);
    }

    /**
     * @test
     *
     * Note Commented out b/c genetic evidence has been removed from the system.
     */
    // public function volunteer_not_assigned_basic_evidence_aptitude_when_assigned_to_baseline_ca()
    // {
    //     $geneticEvidenceAptitude = Aptitude::find(config('aptitudes.baseline-basic-evidence'));
    //     $this->assertUserNotAssignedTo($this->user, $geneticEvidenceAptitude);
    // }
}
