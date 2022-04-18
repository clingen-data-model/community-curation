<?php

namespace Tests\Unit\Services;

use App\Services\Search\VolunteerSearchService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group volunteers
 * @group search
 */
class VolunteerSearchServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();
        $this->seed();
        $this->setupCurationActivity('Gene');
        $this->setupWorkingGroup('Bob');
    }

    /**
     * @test
     */
    public function it_is_instantiable()
    {
        $search = new VolunteerSearchService();

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function it_can_filter_by_is_logged_in()
    {
        $volunteers = $this->createVolunteer([], 10);
        $volunteers->first()->update(['last_logged_in_at' => Carbon::now()->addHours(-1), 'last_logged_out_at' => Carbon::now()->addHours(-2)]);

        $searcher = app()->make(VolunteerSearchService::class);
        $loggedInUsers = $searcher->search(['is_logged_in' => 1]);

        $this->assertEquals(1, $loggedInUsers->count());
        $this->assertEquals($volunteers->first()->id, $loggedInUsers->first()->id);
    }
}
