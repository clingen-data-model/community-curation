<?php

namespace Tests\Unit\Http;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttestationControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
        $this->programmer = factory(User::class)->states('programmer')->create([]);
        $this->admin = factory(User::class)->states('admin')->create([]);
        $this->volunteer = factory(User::class)->states(['volunteer', 'comprehensive'])->create();
        $this->otherVolunteer = factory(User::class)->states('volunteer', 'comprehensive')->create();
        $this->attestation = $this->volunteer->attestations()->create([
            'user_id' => $this->volunteer->id,
            'aptitude_id' => 2,
        ]);
    }
    

    /**
     * @test
     */
    public function volunteer_on_attestation_can_view_attestation()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->volunteer)
            ->call('GET', '/attestations/'.$this->attestation->id)
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function other_volunteer_cannot_view_attestation()
    {
        $this->actingAs($this->otherVolunteer)
            ->call('GET', '/attestations/'.$this->attestation->id)
            ->assertStatus(403);
    }
    

    /**
     * @test
     */
    public function volunteer_on_attestation_can_edit_attestation()
    {
        $this->actingAs($this->volunteer)
            ->call('GET', '/attestations/'.$this->attestation->id.'/edit')
            ->assertStatus(200);

    }

    /**
     * @test
     */
    public function admin_can_update_attesation_for_volunteer()
    {
        $this->actingAs($this->admin, 'api')
            ->call('PUT', '/api/attestations/'.$this->attestation->id, [])
            ->assertStatus(204);
    }
    

    /**
     * @test
     */
    public function other_volunteer__cannot_update_attestation()
    {
        $this->actingAs($this->otherVolunteer, 'api')
            ->call('PUT', '/api/attestations/'.$this->attestation->id, ['date' => Carbon::today()])
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function volunteer_on_attestation_can_update_attestation()
    {
        $this->withoutExceptionHandling();
        $data = ['date' => Carbon::today()->format('Y-m-d H:i:s')];
        $this->actingAs($this->volunteer, 'api')
            ->call('PUT', '/api/attestations/'.$this->attestation->id, $data)
            ->assertStatus(204);

        $this->assertDatabaseHas('attestations', [
            'id' => $this->attestation->id,
            'signed_at' => Carbon::today()->format('Y-m-d H:i:s'),
        ]);

    }


    
}
