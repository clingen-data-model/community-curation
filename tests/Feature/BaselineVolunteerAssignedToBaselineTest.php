<?php

namespace Tests\Feature;

use App\CurationActivity;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group baseline
 */
class BaselineVolunteerAssignedToBaselineTest extends TestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
        $this->volunteer = factory(User::class)->state('baseline')->create([]);
    }

    /**
     * @test
     */
    public function volunteer_assigned_baseline_ca_when_given_type_baseline()
    {
        $this->assertNotNull($this->volunteer->assignments()->first());
        $this->assertEquals(CurationActivity::findByName('baseline'), $this->volunteer->assignments()->first()->assignable);
    }
}
