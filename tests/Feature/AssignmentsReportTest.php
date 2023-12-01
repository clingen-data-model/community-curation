<?php

namespace Tests\Feature;

use App\CurationActivity;
use App\CurationGroup;
use App\Gene;
use App\Jobs\AssignVolunteerToAssignable;
use App\Services\Reports\AssignmentReportGenerator;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tests\TestCase;

/**
 * @group assignments
 * @group reports
 */
class AssignmentsReportTest extends TestCase
{
    use DatabaseTransactions;

    public function setup(): void
    {
        parent::setup();
        // \Event::fake();
        Carbon::setTestNow('2020-01-01 00:00:00');

        $this->curationActivities = CurationActivity::all()->keyBy('id');
        $this->curationGroups = CurationGroup::all()->groupBy('curation_activity_id');

        $this->volunteers = factory(User::class, 3)->states('volunteer', 'comprehensive')->create();

        $this->volunteers->slice(0, 2)->each(function ($volunteer) {
            AssignVolunteerToAssignable::dispatch($volunteer, $this->curationActivities->get(3));
        });
        $this->volunteers->first()->update(['already_clingen_member' => 1, 'already_member_cgs' => ['23', '41']]);

        AssignVolunteerToAssignable::dispatch($this->volunteers->first(), $this->curationActivities->get(1));
        AssignVolunteerToAssignable::dispatch($this->volunteers->first(), $this->curationActivities->get(2));

        AssignVolunteerToAssignable::dispatch($this->volunteers->get(1), $this->curationGroups->get(3)->first());
        AssignVolunteerToAssignable::dispatch($this->volunteers->get(1), $this->curationGroups->get(3)->last());

        $this->genes = factory(Gene::class, 2)->create();

        AssignVolunteerToAssignable::dispatch($this->volunteers->last(), $this->curationActivities->get(6));
        AssignVolunteerToAssignable::dispatch($this->volunteers->last(), $this->genes->first());
        AssignVolunteerToAssignable::dispatch($this->volunteers->last(), $this->genes->last());

        $this->date = Carbon::now();

        $appRsp = class_survey()::findBySlug('application1')->getNewResponse($this->volunteers->get(1));
        $appRsp->fill([
            'email' => 'test@test.com',
            'first_name' => 'test',
            'last_name' => 'testerson',
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
        $report = app()->make(AssignmentReportGenerator::class)->generate([]);

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
                'hypothesis_id' => $vol->hypothesis_id,
                'current_status' => $vol->volunteerStatus->name,
                'ca_assignments' => 1,
                'survey_completion_date' => $this->date->format('Y-m-d H:i:s'),
                'curation_activity_id' => $this->curationActivities->get(3)->id,
                'curation_activity' => $this->curationActivities->get(3)->name,
                'training_completion_date' => $this->date,
                'attestation_date' => $this->date,
                'assigned_curation_group' => $this->curationGroups->get(3)->first()->name
                                            .",\n"
                                            .$this->curationGroups->get(3)->last()->name,
                'assigned_gene' => null, //$this->genes->first()->symbol.",\n".$this->genes->last()->symbol
                'already_clingen_member' => $vol->already_clingen_member,
                'already_member_cgs' => $vol->memberGroups->pluck('name')->join(', '),
            ],
            $testRow
        );
    }

    /**
     * @test
     */
    public function includes_gene_assignments()
    {
        $report = app()->make(AssignmentReportGenerator::class)->generate([]);

        $vol = $this->volunteers->get(2)->fresh();
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
                'hypothesis_id' => $vol->hypothesis_id,
                'current_status' => $vol->volunteerStatus->name,
                'ca_assignments' => 1,
                'survey_completion_date' => null,
                'curation_activity_id' => $this->curationActivities->get(config('project.curation-activities.baseline'))->id,
                'curation_activity' => $this->curationActivities->get(config('project.curation-activities.baseline'))->name,
                'training_completion_date' => null,
                'attestation_date' => null,
                'assigned_curation_group' => '',
                'assigned_gene' => $this->genes->first()->symbol.",\n".$this->genes->last()->symbol,
                'already_clingen_member' => $vol->already_clingen_member,
                'already_member_cgs' => $vol->memberGroups->pluck('name')->join(', '),
            ],
            $testRow
        );
    }

    /**
     * @test
     */
    public function first_sheet_of_report_has_all_volunteers()
    {
        $report = app()->make(AssignmentReportGenerator::class)->generate([]);

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
        $report = app()->make(AssignmentReportGenerator::class)->generate([]);

        $this->assertContains('Actionability', $report->keys()->toArray());
    }

    /**
     * @test
     */
    public function curation_activity_rows_only_include_rows_for_that_curation_activity()
    {
        $report = app()->make(AssignmentReportGenerator::class)->generate([]);

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
        $user->assignRole('programmer');

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
