<?php

namespace Tests\Unit\Http\Api;

use App\Preference;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group preferences
 */
class UserPreferenceControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $preference;
    private $volunteer;

    public function setup(): void
    {
        parent::setup();
        $this->preference = factory(Preference::class)->create(['data_type' => 'int']);
        $this->volunteer = $this->createVolunteer();
    }

    /**
     * @test
     */
    public function stores_and_returns_new_user_preference_entry()
    {
        $this->actingAs($this->volunteer, 'api')
            ->call('PUT', '/api/users/'.$this->volunteer->id.'/preferences/'.$this->preference->name, ['value' => true])
            ->assertOk()
            ->assertJsonFragment([
                [
                    'preference_id' => $this->preference->id,
                    'name' => $this->preference->name,
                    'value' => 1,
               ],
            ]);

        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $this->volunteer->id,
            'preference_id' => $this->preference->id,
            'value' => 1,
        ]);
    }

    /**
     * @test
     */
    public function updates_and_returns_new_preference_entry()
    {
        $this->volunteer->setPreference($this->preference->name, 1);

        $this->actingAs($this->volunteer, 'api')
            ->call('PUT', '/api/users/'.$this->volunteer->id.'/preferences/'.$this->preference->name, ['value' => 2])
            ->assertOk()
            ->assertJsonFragment([
                [
                    'preference_id' => $this->preference->id,
                    'name' => $this->preference->name,
                    'value' => 2,
               ],
            ]);

        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $this->volunteer->id,
            'preference_id' => $this->preference->id,
            'value' => true,
        ]);
    }
}
