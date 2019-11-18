<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use App\Attestation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group attestations
 */
class GrantAptitudeWhenAttestationSignedTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();
        $this->volunteer = factory(User::class)->states('volunteer', 'comprehensive')->create();
        $this->attestation = factory(Attestation::class)->create([
            'user_id' => $this->volunteer,
            'aptitude_id' => 1
        ]);
    }
    
    /**
     * @test
     */
    public function aptitude_associated_with_user_when_attestation_is_signed()
    {
        $this->attestation->update([
            'signed_at' => Carbon::now()
        ]);

        $this->assertDatabaseHas('aptitude_user', [
            'user_id' => $this->volunteer->id,
            'aptitude_id' => 1
        ]);
    }
    

}
