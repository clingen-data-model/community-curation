<?php

namespace Tests\Unit\Models;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->programmer1 = factory(User::class)->create();
        $this->programmer1->assignRole('programmer');

        $this->programmer2 = factory(User::class)->create();
        $this->programmer2->assignRole('programmer');
        
        $this->admin1 = factory(User::class)->create();
        $this->admin1->assignRole('admin');

        $this->admin2 = factory(User::class)->create();
        $this->admin2->assignRole('admin');

        $this->coordinator = factory(User::class)->create();
        $this->coordinator->assignRole('coordinator');

        $this->volunteer = factory(User::class)->create();
        $this->volunteer->assignRole('volunteer');
    }
    

    /**
     * @test
     */
    public function programmer_can_impersonate_anyone_whos_not_a_programmer()
    {
        $this->actingAs($this->programmer1);
        
        $this->assertTrue($this->admin1->canBeImpersonated());
        $this->assertTrue($this->admin2->canBeImpersonated());
        $this->assertTrue($this->coordinator->canBeImpersonated());
        $this->assertFalse($this->programmer2->canBeImpersonated());
    }

    /**
     * @test
     */
    public function admin_cannot_impersonate_programmer()
    {
        $this->actingAs($this->admin1);
        $this->assertFalse($this->programmer1->canBeImpersonated());
    }

    /**
     * @test
     */
    public function admin_cannot_impersonate_another_admin()
    {
        $this->actingAs($this->admin2);
        $this->assertFalse($this->admin1->canBeImpersonated());
    }

    /**
     * @test
     */
    public function admin_can_impersonate_coordinator_or_volunteer()
    {
        $this->actingAs($this->admin1);
        $this->assertTrue($this->coordinator->canBeImpersonated());
        $this->assertTrue($this->volunteer->canBeImpersonated());
    }
    
    /**
     * @test
     */
    public function scopesUsersToVolunteers()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        \DB::table('volunteers')->truncate();
        \DB::table('users')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        factory(User::class, 2)->create()->each(function ($u) {
            $u->assignRole('admin');
        });
        factory(User::class, 2)->create()->each(function ($u) {
            $u->assignRole('volunteer');
            $u->volunteer()->create();
        });

        $this->assertEquals(2, User::isVolunteer()->count());
    }
        
}
