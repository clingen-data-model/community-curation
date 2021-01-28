<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use App\WorkingGroup;
use App\CurationGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteWorkingGroupTest extends TestCase
{
    use DatabaseTransactions;
    
    public function setup():void
    {
        parent::setup();
        $this->user = factory(User::class)->states('admin')->create();
        $this->wg = factory(WorkingGroup::class)->create();
    }

    /**
     * @test
     */
    public function checks_for_eps_before_deleting_working_group()
    {
        $ep = factory(CurationGroup::class)->make();
        $this->wg->curationGroups()->save($ep);

        $this->actingAs($this->user, 'api')
            ->json('DELETE', 'admin/working-group/'.$this->wg->id)
            ->assertStatus(302)
            ->assertSessionHas('error', 'Working group has curation groups associated with it. You must delete those curation groups before you can delete the working group.');
    }
    
    
}
