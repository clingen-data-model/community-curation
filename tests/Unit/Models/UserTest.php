<?php

namespace Tests\Unit\Models;

use App\Contracts\IsNotable;
use App\CurationGroup;
use App\Priority;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * @group user
 */
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
        $admin = factory(User::class)->state('admin')->create([]);

        $coordinator = factory(User::class)->state('coordinator')->create([]);
        $volunteer = factory(User::class)->state('volunteer')->create([]);

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
        \DB::table('users')->delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $admin = factory(User::class)->create();
        $admin->roles()->sync([]);
        factory(User::class)->states('volunteer')->create();
        factory(User::class)->states('volunteer')->create();

        $this->assertEquals(2, User::isVolunteer()->count());
    }

    /**
     * @test
     */
    public function gets_latest_priorities_relation()
    {
        $volunteer1 = factory(User::class)->states('volunteer', 'comprehensive')->create();
        $firstPriorities = Priority::factory(3)->create([
            'user_id' => $volunteer1->id,
        ]);
        $secondPriorities = Priority::factory(3)->create([
            'user_id' => $volunteer1->id,
            'prioritization_round' => 2,
        ]);

        $latestPriorities = $volunteer1->fresh()->latestPriorities;

        $this->assertEquals($secondPriorities->pluck('id'), $latestPriorities->pluck('id'));
        $this->assertNotContains($firstPriorities->first()->id, $latestPriorities->pluck('id'));
    }

    /**
     * @test
     */
    public function has_nullable_fillable_orcid_id()
    {
        $volunteer = factory(User::class)->create(['orcid_id' => null]);
        $volunteer->update(['orcid_id' => 'test']);

        $this->assertEquals('test', $volunteer->orcid_id);
    }

    /**
     * @test
     * @group notes
     */
    public function implements_notable_trait()
    {
        $volunteer = $this->createVolunteer();
        $this->assertInstanceOf(IsNotable::class, $volunteer);
    }

    /**
     * @test
     * @group login
     */
    public function can_scope_to_logged_in_users()
    {
        $user = $this->createVolunteer();
        $volunteer = $this->createVolunteer(['last_logged_in_at' => Carbon::now()->addHours(-2)]);
        $programmer = $this->createProgrammer(['last_logged_in_at' => Carbon::now()->addHours(-2), 'last_logged_out_at' => Carbon::now()->addHours(-4)]);
        $admin = $this->createAdmin(['last_logged_in_at' => Carbon::now()->addHours(-2), 'last_logged_out_at' => Carbon::now()->addHours(-1)]);

        $loggedInUsers = User::isLoggedIn()->get();

        $this->assertEquals(2, $loggedInUsers->count());
        $this->assertEquals([$volunteer->id, $programmer->id], $loggedInUsers->pluck('id')->values()->toArray());
    }

    /**
     * @test
     */
    public function status_set_to_applied_on_created_if_not_set_and_volunteer_type_not_null()
    {
        $user = factory(User::class)->states(['volunteer', 'comprehensive'])->make([
            'volunteer_status_id' => null,
        ]);

        $this->assertEquals(null, $user->volunteer_status_id);

        $user->save();

        $this->assertEquals(config('project.volunteer-statuses.applied'), $user->volunteer_status_id);
    }

    /**
     * @test
     */
    public function soft_deletes_related_application_survey_response_when_user_deleted()
    {
        $user = $this->createVolunteer();
        $survey = class_survey()::find(1);
        $response = $survey->getLatestResponse($user);
        $user->application()->save($response);

        $this->assertNotNull($user->application()->get());

        $user->delete();

        $this->assertDatabaseHas('rsp_application_1', [
            'id' => $response->id,
        ]);

        $this->assertDatabaseMissing('rsp_application_1', [
            'id' => $response->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * @test
     */
    public function setAlreadyMemberCgsAttribute_accepts_null_value()
    {
        $user = $this->makeVolunteer()->first();
        $user->already_member_cgs = null;

        $this->assertNull($user->getAttributes()['already_member_cgs']);
    }

    /**
     * @test
     * @group already-member-flag
     */
    public function getAlreadyMemberCurationGroups_returns_empty_collection_when_already_member_cgs_is_null()
    {
        $user = $this->makeVolunteer()->first();
        $user->already_member_cgs = null;

        $this->assertInstanceOf(Collection::class, $user->getAlreadyMemberCurationGroups());
        $this->assertEquals(0, $user->getAlreadyMemberCurationGroups()->count());
    }

    /**
     * @test
     * @group already-member-flag
     */
    public function getAlreadyMemberCurationGroups_returns_curation_group_models_for_already_member_cgs()
    {
        $eps = CurationGroup::factory(2)->create();
        $user = $this->makeVolunteer()->first();
        $user->already_member_cgs = $eps->pluck('id')->toArray();

        $this->assertEquals($eps->pluck('name', 'id'), $user->getAlreadyMemberCurationGroups()->pluck('name', 'id'));
    }
}
