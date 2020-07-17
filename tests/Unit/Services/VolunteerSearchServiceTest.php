<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\Search\VolunteerSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * @group volunteers
 * @group search
 */
class VolunteerSearchServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function setup():void
    {
        parent::setup();
        //setup code
    }
    
    /**
     * @test
     */
    public function it_is_instantiable()
    {
        $search = new VolunteerSearchService();
        
        $this->assertTrue(true);
    }
}
