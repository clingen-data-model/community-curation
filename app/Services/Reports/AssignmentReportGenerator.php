<?php

namespace App\Services\Reports;

use App\User;
use App\CurationActivity;
use App\Contracts\ReportGenerator;
use App\ExpertPanel;
use Illuminate\Support\Collection;

class AssignmentReportGenerator implements ReportGenerator
{
    public function generate():Collection
    {
        $volunteers = User::query()
                        ->with([
                            'country', 
                            'volunteerStatus', 
                            'assignments', 
                            'assignments.status', 
                            'assignments.trainings', 
                            'assignments.assignable',
                            'assignments.attestations',
                            'application'
                        ])
                        ->isVolunteer()
                        ->get();

        // dd($volunteers->toArray());
        
        $allRows = $volunteers->transform(function ($volunteer) {
            $root = [
                'email' => $volunteer->email,
                'first_name' => $volunteer->first_name,
                'last_name' => $volunteer->last_name,
                'country' => ($volunteer->country) ? $volunteer->country->name : null,
                'state' => $volunteer->state,
                'city' => $volunteer->city,
                'current_status' => $volunteer->volunteerStatus ? $volunteer->volunteerStatus->name : 'MISSING WTF?!',
                'ca_assignments' => $volunteer->assignments->isCurationActivity()->count(),
                'survey_completion_date' => ($volunteer->application) ? $volunteer->application->finalized_at : null,
            ];
            return $volunteer->assignments
                ->isCurationActivity()
                ->transform(function ($assignment) use ($root, $volunteer) {
                    return array_merge(
                            $root, 
                            [
                                'curation_activity_id' => $assignment->assignable->id,
                                'curation_activity' => $assignment->assignable->name,
                                'training_completion_date' => ($assignment->trainings->first()) ? $assignment->trainings->first()->completed_at : null,
                                'attestation_date' => ($assignment->attestations->first()) ? $assignment->attestations->first()->signed_at : null,
                                'assigned_expert_panel' => $volunteer->assignments
                                                                ->filter(function ($item) use ($assignment) {
                                                                    return $item->assignable_type == ExpertPanel::class
                                                                        && $item->assignable->curation_activity_id == $assignment->assignable_id;
                                                                })
                                                                ->pluck('assignable.name')
                                                                ->join(",\n"),
                            ]
                        );
                });
        })->flatten(1);

        // dd($allRows);
        
        $data = collect([
            'all' => $allRows
        ]);
        
        $caSheets = CurationActivity::all()
            ->keyBy('name')
            ->transform(function ($ca) use ($allRows) {
                return $allRows->filter(function ($row) use ($ca) {
                    return $row['curation_activity_id'] == $ca->id;
                });
            });
        $data = $data->merge($caSheets);

        return $data;
    }
    
}