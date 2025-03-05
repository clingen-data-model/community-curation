<?php

namespace App\Services\Reports;

use App\Gene;
use App\CurationGroup;
use App\CurationActivity;
use App\Contracts\ReportGenerator;
use Illuminate\Support\Collection;
use App\Services\Search\VolunteerSearchService;

class AssignmentReportGenerator implements ReportGenerator
{
    protected $searchService;

    public function __construct(VolunteerSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function generate($filterParams = []): Collection
    {
        $volunteers = $this->getVolunteers($filterParams);

        $allRows = $volunteers->transform(function ($volunteer) {
            return $this->convertToRow($volunteer);
        })->flatten(1);

        $data = collect([
            'all' => $allRows,
        ]);

        $caSheets = CurationActivity::all()
            ->keyBy('name')
            ->transform(function ($ca) use ($allRows) {
                return $allRows->filter(function ($row) use ($ca) {
                    return $row['curation_activity_id'] == $ca->id;
                });
            });
        $data = $data->merge($caSheets);
        $data['unassigned'] = $allRows->filter(function ($item) {
            return is_null($item['curation_activity_id']);
        });

        return $data;
    }

    private function convertToRow($volunteer)
    {
        $base = [
            'volunteer_id' => $volunteer->id,
            'email' => $volunteer->email,
            'first_name' => $volunteer->first_name,
            'last_name' => $volunteer->last_name,
            'country' => ($volunteer->country) ? $volunteer->country->name : null,
            'state' => $volunteer->state,
            'city' => $volunteer->city,
            'hypothesis_id' => $volunteer->hypothesis_id,
            'current_status' => $volunteer->volunteerStatus ? $volunteer->volunteerStatus->name : 'MISSING WTF?!',
            'ca_assignments' => $volunteer->assignments->isCurationActivity()->count(),
            'survey_completion_date' => ($volunteer->application) ? $volunteer->application->finalized_at : null,
        ];

        if ($volunteer->assignments->count() == 0) {
            return collect([array_merge(
                $base,
                [
                    'curation_activity_id' => null,
                    'curation_activity' => null,
                    'training_completion_date' => null,
                    'attestation_date' => null,
                    'assigned_curation_group' => null,
                ]
            )]);
        }

        return $volunteer->assignments
            ->isCurationActivity()
            ->transform(function ($assignment) use ($base, $volunteer) {
                return array_merge(
                    $base,
                    [
                            'curation_activity_id'      => $assignment->assignable->id,
                            'curation_activity'         => $assignment->assignable->name,
                            'training_completion_date'  => ($assignment->userAptitudes->first())
                                                            ? $assignment->userAptitudes->first()->trained_at
                                                            : null,
                            'attestation_date'          => ($assignment->attestations->first())
                                                            ? $assignment->attestations->first()->signed_at
                                                            : null,
                            'assigned_curation_group'   => $volunteer->assignments
                                                            ->filter(function ($item) use ($assignment) {
                                                                return $item->assignable_type == CurationGroup::class
                                                                    && $item->assignable->curation_activity_id == $assignment->assignable_id;
                                                            })
                                                            ->pluck('assignable.name')
                                                            ->join(",\n"),
                            'assigned_gene'             => $volunteer->assignments
                                                            ->filter(function ($item) use ($assignment) {
                                                                return $item->assignable_type == Gene::class;
                                                            })
                                                            ->pluck('assignable.name')
                                                            ->join(",\n"),
                            'already_clingen_member'    => $volunteer->already_clingen_member,
                            'already_member_cgs'        => $volunteer->memberGroups->pluck('name')->join(', ')
                        ]
                );
            });
    }


    private function getVolunteers($filterParams)
    {
        $params = array_merge([
            'with' => [
                'country',
                'volunteerStatus',
                'assignments',
                'assignments.status',
                'assignments.userAptitudes',
                'assignments.assignable',
                'assignments.attestations',
                'application',
            ],
            ], $filterParams);
        return $this->searchService->search($params);
    }
}
