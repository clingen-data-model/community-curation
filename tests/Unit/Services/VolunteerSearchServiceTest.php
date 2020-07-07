<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\Reports\VolunteerSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group volunteers
 * @group search
 */
class VolunteerSearchServiceTest extends TestCase
{
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
