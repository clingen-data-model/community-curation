<?php

namespace Tests\Unit\Aptitues\Evaluators;

use App\Aptitudes\Evaluators\BasicAptitudeEvaluator;
use App\Attestation;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group aptitudes
 */
class BasicAptitudeEvaluatorTest extends TestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
        $this->volunteer = factory(User::class)->states('volunteer', 'baseline')->create();
        $this->userApt = $this->volunteer->userAptitudes->first();
    }

    /**
     * @test
     */
    public function user_aptitude_not_granted_when_trained_at_is_null()
    {
        $this->assertFalse((new BasicAptitudeEvaluator($this->userApt))->meetsCriteria());
    }

    /**
     * @test
     */
    public function user_aptitude_not_granted_if_attestation_id_is_null()
    {
        $userApt = $this->volunteer->userAptitudes->first();
        $userApt->update([
            'trained_at' => Carbon::now(),
        ]);

        $this->assertFalse((new BasicAptitudeEvaluator($this->userApt))->meetsCriteria());
    }

    /**
     * @test
     */
    public function user_aptitude_not_granted_if_attestation_exists_but_not_signed()
    {
        $userApt = $this->volunteer->userAptitudes->first();
        $attestation = factory(Attestation::class)->create([
            'aptitude_id' => $userApt->aptitude_id,
        ]);

        $userApt->update([
            'trained_at' => Carbon::now(),
            'attestation_id' => $attestation->id,
        ]);

        $this->assertFalse((new BasicAptitudeEvaluator($this->userApt))->meetsCriteria());
    }

    /**
     * @test
     */
    public function user_aptitude_granted_if_attestation_exists_and_signed()
    {
        $userApt = $this->volunteer->userAptitudes->first();
        $attestation = factory(Attestation::class)->create([
            'aptitude_id' => $userApt->aptitude_id,
            'signed_at' => Carbon::now(),
        ]);

        $userApt->update([
            'trained_at' => Carbon::now(),
            'attestation_id' => $attestation->id,
        ]);

        $this->assertTrue((new BasicAptitudeEvaluator($this->userApt))->meetsCriteria());
    }
}
