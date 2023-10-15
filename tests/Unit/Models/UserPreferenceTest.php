<?php

namespace Tests\Unit\Models;

use App\Preference;
use App\UserPreference;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group preferences
 */
class UserPreferenceTest extends TestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
    }

    private function assertCastsValueToPrefDataFormat(Preference $preference, $value, $type = null)
    {
        $up = new UserPreference([
            'preference_id' => $preference->id,
            'value' => $value,
        ]);

        $type = $type ? $type : $preference->data_type;

        $this->assertEquals($type, gettype($up->getAttributes()['value']));
    }

    /**
     * @test
     */
    public function casts_int_value_to_int_on_set(): void
    {
        $pref = factory(Preference::class)->create(['data_type' => 'integer']);
        $this->assertCastsValueToPrefDataFormat($pref, '1');
    }

    /**
     * @test
     */
    public function casts_str_value_to_str_on_set(): void
    {
        $pref = factory(Preference::class)->create(['data_type' => 'string']);
        $this->assertCastsValueToPrefDataFormat($pref, 1);
    }

    /**
     * @test
     */
    public function casts_float_value_to_float_on_set(): void
    {
        $pref = factory(Preference::class)->create(['data_type' => 'float']);
        $this->assertCastsValueToPrefDataFormat($pref, 1, 'double');
    }

    /**
     * @test
     */
    public function casts_bool_value_on_set(): void
    {
        $pref = factory(Preference::class)->create(['data_type' => 'boolean']);
        $this->assertCastsValueToPrefDataFormat($pref, 1);
    }

    /**
     * @test
     */
    public function casts_array_and_json_encodes_value_on_set(): void
    {
        $pref = factory(Preference::class)->create(['data_type' => 'array']);
        $up = new UserPreference([
            'preference_id' => $pref->id,
        ]);

        $up->value = 1;

        $this->assertIsString($up->getAttributes()['value']);
        $this->assertEquals([1], json_decode($up->getAttributes()['value']));
    }

    /**
     * @test
     */
    public function casts_object_and_json_encodes_value_on_set(): void
    {
        $pref = factory(Preference::class)->create(['data_type' => 'object']);
        $up = new UserPreference([
            'preference_id' => $pref->id,
        ]);

        $up->value = ['test' => 1];

        $this->assertIsString($up->getAttributes()['value']);
        $this->assertEquals((object) ['test' => 1], json_decode($up->getAttributes()['value']));
    }

    /**
     * @test
     */
    public function json_decodes_array_value_on_get(): void
    {
        $pref = factory(Preference::class)->create(['data_type' => 'array']);
        $up = new UserPreference(['preference_id' => $pref->id, 'value' => [1, 2, 3]]);
        $this->assertIsArray($up->value);
    }

    /**
     * @test
     */
    public function json_decodes_object_value_on_get(): void
    {
        $pref = factory(Preference::class)->create(['data_type' => 'object']);
        $value = (object) ['a' => 1, 'b' => 2, 'c' => 3];
        $up = new UserPreference(['preference_id' => $pref->id, 'value' => $value]);
        $this->assertIsObject($up->value);
    }
}
