<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\ExpertPanel;
use App\CurationActivity;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Jobs\AssignVolunteerToAssignable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Reports\AssignmentReportGenerator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @group assignments
 * @group reports
 */
class AssignmentsReportTest extends TestCase
{
    use DatabaseTransactions;

    public function setup():void
    {
        parent::setup();
        // \Event::fake();
        Carbon::setTestNow('2020-01-01 00:00:00');

        $this->curationActivities = CurationActivity::all()->keyBy('id');
        $this->expertPanels = ExpertPanel::all()->groupBy('curation_activity_id');

        $this->volunteers = factory(User::class, 3)->states('volunteer', 'comprehensive')->create();

        $this->volunteers->each(function ($volunteer) {
            AssignVolunteerToAssignable::dispatch($volunteer, $this->curationActivities->get(3));
        });

        AssignVolunteerToAssignable::dispatch($this->volunteers->first(), $this->curationActivities->get(1));
        AssignVolunteerToAssignable::dispatch($this->volunteers->first(), $this->curationActivities->get(2));

        AssignVolunteerToAssignable::dispatch($this->volunteers->get(1), $this->expertPanels->get(3)->first());
        AssignVolunteerToAssignable::dispatch($this->volunteers->get(1), $this->expertPanels->get(3)->last());

        $this->date = Carbon::now();

        $appRsp = class_survey()::findBySlug('application1')->getNewResponse($this->volunteers->get(1));
        $appRsp->fill([
            'email' => 'test@test.com',
            'first_name' => 'test',
            'last_name' => 'testerson'
        ]);
        $appRsp->save();
        $appRsp->finalize();

        $this->volunteers->get(1)
            ->fresh()
            ->userAptitudes()
            ->get()
            ->first()
            ->update(['trained_at' => $this->date]);

        $this->volunteers->get(1)->fresh()->attestations()
            ->get()
            ->first()
            ->update(['signed_at' => $this->date]);
    }
 
    /**
     * @test
     */
    public function rows_have_all_relevant_data()
    {
        $report = (new AssignmentReportGenerator)->generate();
        
        $vol = $this->volunteers->get(1)->fresh();
        $testRow = $report->get('all')
                    ->filter(function ($row) use ($vol) {
                        return $row['email'] == $vol->email;
                    })
                    ->first();

        $this->assertEquals(
            [
                'email' => $vol->email,
                'first_name' => $vol->first_name,
                'last_name' => $vol->last_name,
                'country' => $vol->country->name,
                'state' => $vol->state,
                'city' => $vol->city,
                'current_status' => $vol->volunteerStatus->name,
                'ca_assignments' => 1,
                'survey_completion_date' => $this->date->format('Y-m-d H:i:s'),
                'curation_activity_id' => $this->curationActivities->get(3)->id,
                'curation_activity' => $this->curationActivities->get(3)->name,
                'training_completion_date' => $this->date,
                'attestation_date' => $this->date,
                'assigned_expert_panel' => $this->expertPanels->get(3)->first()->name
                                            .",\n"
                                            .$this->expertPanels->get(3)->last()->name
            ],
            $testRow
        );
    }
    

    /**
     * @test
     */
    public function first_sheet_of_report_has_all_volunteers()
    {
        $report = (new AssignmentReportGenerator)->generate();

        $this->assertInstanceOf(Collection::class, $report);

        $this->assertTrue($report->get('all')->contains('email', $this->volunteers->get(0)->email));
        $this->assertTrue($report->get('all')->contains('email', $this->volunteers->get(1)->email));
        $this->assertTrue($report->get('all')->contains('email', $this->volunteers->get(2)->email));
    }

    /**
     * @test
     */
    public function includes_a_sheet_for_each_curation_activity()
    {
        $report = (new AssignmentReportGenerator)->generate();

        $this->assertContains('Actionability', $report->keys()->toArray());
    }

    /**
     * @test
     */
    public function curation_activity_rows_only_include_rows_for_that_curation_activity()
    {
        $report = (new AssignmentReportGenerator)->generate();
        
        $sheetName = $this->curationActivities->get(3)->name;
        $caSheet = $report->get($sheetName);
        
        $curationActivities = $caSheet->pluck('curation_activity')->unique();

        $this->assertEquals(1, $curationActivities->count());
        $this->assertEquals($sheetName, $curationActivities->first());
    }

    /**
     * @test
     */
    public function assignments_report_endpoint_returns_an_xlsx_file()
    {
        $user = factory(User::class)->create([]);

        $this->withoutExceptionHandling();
        $response = $this->actingAs($user)
            ->call('GET', 'assignments-report')
            ->assertStatus(200);

        // assert that the baseResponse object is a Symfony BinaryFileResponse
        $this->assertInstanceOf(BinaryFileResponse::class, $response->baseResponse);
        $this->assertEquals(storage_path('app/reports'), $response->baseResponse->getFile()->getPath());
        $this->assertEquals('xlsx', $response->baseResponse->getFile()->getExtension());
    }
}
