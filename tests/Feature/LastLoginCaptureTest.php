<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group login
 * @group activity-logging
 */
class LastLoginCaptureTest extends TestCase
{
    use DatabaseTransactions;

    public function setup():void
    {
        parent::setup();
        $this->user = $this->createVolunteer();
        Carbon::setTestNow('2020-07-29 00:00:00');
    }

    /**
     * @test
     */
    public function sets_last_logged_in_at_date()
    {
        Auth::loginUsingId($this->user->id);

        $user = $this->user->fresh();

        $this->assertNotNull($user->last_logged_in_at);
        $this->assertEquals(Carbon::now(), $user->last_logged_in_at);
    }
}
