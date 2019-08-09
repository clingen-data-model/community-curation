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

    /**
     * @test
     */
    public function programmer_can_impersonate_anyone_whos_not_a_programmer()
    {
        $prog = factory(User::class)->states('programmer')->create();
        $prog2 = factory(User::class)->states('programmer')->create([]);
        
        $ad1 = factory(User::class)->states('admin')->create();
        $ad2 = factory(User::class)->states('admin')->create();

        $coord = factory(User::class)->states('coordinator')->create([]);

        $this->actingAs($prog->fresh());
        $this->assertTrue($ad1->canBeImpersonated());
        $this->assertTrue($ad2->canBeImpersonated());
        $this->assertTrue($coord->canBeImpersonated());
        $this->assertFalse($prog2->canBeImpersonated());
    }

    /**
     * @test
     */
    public function admin_cannot_impersonate_programmer()
    {
        $admin = factory(User::class)->states('admin')->create([]);
        $prog = factory(User::class)->states('programmer')->create([]);

        $this->actingAs($admin);
        $this->assertFalse($prog->canBeImpersonated());
    }

    /**
     * @test
     */
    public function admin_cannot_impersonate_another_admin()
    {
        $admin1 = factory(User::class)->states('admin')->create([]);
        $admin2 = factory(User::class)->states('admin')->create([]);
        $this->actingAs($admin2);
        $this->assertFalse($admin1->canBeImpersonated());
    }

    /**
     * @test
     */
    public function admin_can_impersonate_coordinator_or_volunteer()
    {
        $admin = factory(User::class)->states('admin')->create([]);
        $coordinator = factory(User::class)->states('coordinator')->create([]);
        $volunteer = factory(User::class)->states('volunteer')->create([]);

        $this->actingAs($admin);
        $this->assertTrue($coordinator->canBeImpersonated());
        $this->assertTrue($volunteer->canBeImpersonated());
    }
    
    /**
     * @test
     */
    public function scopesUsersToVolunteers()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        \DB::table('users')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $admin = factory(User::class)->create();
        $admin->roles()->sync([]);
        $volunteer1 = factory(User::class)->states('volunteer')->create();
        $volunteer2 = factory(User::class)->states('volunteer')->create();

        $this->assertEquals(2, User::isVolunteer()->count());

    }
        
}
