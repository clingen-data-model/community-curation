<?php

namespace App\Services\Reports;

use App\Application;
use App\Contracts\ReportGenerator;
use App\Country;
use App\Services\Search\VolunteerSearchService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ApplicationReportGenerator implements ReportGenerator
{
    protected $applicationQuestions;
    protected $applicationQuestionResponses;
    protected $volunteerSearchService;
    protected $query;

    public function __construct(VolunteerSearchService $volunteerSearchService)
    {
        $this->volunteerSearchService = $volunteerSearchService;
        $this->query = Application::query()
                        ->hasRespondent()
                        ->with('user', 'country', 'user.priorities', 'user.priorities.curationActivity', 'user.priorities.curationGroup')->finalized();
        $this->applicationQuestions = class_survey()::findBySlug('application1')->getQuestions();
        $this->applicationQuestionResponses = [];
        foreach ($this->applicationQuestions as $question) {
            if (!$question->hasOptions()) {
                continue;
            }
            $this->applicationQuestionResponses[$question->getName()] = [];
            foreach ($question->getOptions() as $option) {
                $this->applicationQuestionResponses[$question->getName()][$option->value] = $option->label;
            }
        }
    }

    public function generate($filterParams = []): Collection
    {
        if ($filterParams) {
            $this->filterByVolunteer($filterParams);
        }

        Log::info('Application Report Query: '.$this->query->toSql());
        $applications = $this->query->get();
        Log::info('finished query');

        $rawData = $applications->map(function ($application) {
            foreach ($this->applicationQuestions as $definition) {
                $qName = $definition->getName();
                $response = $this->getReadableResponse($application->{$qName}, $definition);
                $application->{$qName} = $response ? $response : '';
            }

            return $application;
        });
        Log::info('finished mapping readable responses');

        $tidyData = $this->tidyUpData($rawData);
        $data = collect();
        if ($tidyData->count() > 0) {
            foreach (array_keys($tidyData->first()) as $group) {
                $data->put($group, $tidyData->pluck($group));
            }
        }
        Log::info('finished tidying up data');

        return $data;
    }

    public function addConstraint(callable $func)
    {
        $func($this->query);

        return $this;
    }

    private function tidyUpData(Collection $data): Collection
    {
        $countries = Country::all()->pluck('name', 'id');

        return $data->map(function ($app) use ($countries) {
            $introColumns = collect([
                'volunteer_id' => $app->respondent_id,
                'first_name' => $app->first_name,
                'last_name' => $app->last_name,
                'email' => $app->email,
            ]);
            $outroColumns = collect([
                'date_completed' => $app->finalized_at,
                'imported_from_google_sheets' => !is_null($app->imported_survey_data) ? 'Yes' : 'No',
            ]);

            return [
                'personal' => $introColumns
                                    ->merge([
                                            'institution' => $app->respondent->institution,
                                            'orcid_id' => $app->respondent->orcid_id,
                                            'hypothesis_id' => $app->hypothesis_id,
                                            'street1' => $app->respondent->street1,
                                            'street2' => $app->respondent->street2,
                                            'city' => $app->respondent->city,
                                            'state' => $app->respondent->state,
                                            'zip' => $app->respondent->zip,
                                            'country' => ($app->respondent->country) ? $app->respondent->country->name : null,
                                            'timezone' => $app->respondent->timezone,
                                    ])->merge($outroColumns),

                'professional' => $introColumns
                                    ->merge([
                                            'volunteer_id' => $app->respondent_id,
                                            'heighest_ed' => ($app->highest_ed) ? $app->highest_ed : '',
                                            'heighest_ed_other' => $app->highest_ed_other,
                                            'advanced_certification' => $app->adv_cert,
                                            'self_description' => $app->self_desc ? $app->self_desc : '',
                                            'self_description_other' => $app->self_desc_other,
                                            'already_clingen_member' => $app->respondent->already_clingen_member,
                                            'already_member_cgs' => $app->respondent->memberGroups->pluck('name')->join(', ')
                                    ])->merge($outroColumns),

                'demographic' => $introColumns
                                    ->merge($this->getQuestionColumns('race_ethnicity', $app))
                                    ->merge(['other' => $app->race_ethnicity_other_detail])
                                    ->merge($outroColumns),

                'outreach' => $introColumns
                                    ->merge($this->getQuestionColumns('ad_campaign', $app))
                                    ->merge(['other' => $app->ad_campaign_other])
                                    ->merge($outroColumns),

                'motivation' => $introColumns
                                    ->merge($this->getQuestionColumns('motivation', $app))
                                    ->merge(['other' => $app->motivation_other])
                                    ->merge($outroColumns),

                'goals' => $introColumns
                                    ->merge($this->getQuestionColumns('goals', $app))
                                    ->merge(['other' => $app->goals_other])
                                    ->merge($outroColumns),

                'interests' => $introColumns
                                    ->merge($this->getQuestionColumns('interests', $app))
                                    ->merge($outroColumns),

                'ccdb' => $introColumns
                                    ->merge([
                                        'baseline/comprehensive' => $app->volunteer_type,
                                    ])
                                    ->merge($this->getPriorityData($app))
                                    ->merge($outroColumns),
            ];
        });
    }

    private function getPriorityData($app)
    {
        $data = [];
        // if (!$app->respondent) {
        //     dd($app->toArray());
        // }
        $priorities = $app->respondent->latestPriorities->values();

        for ($i = 0; $i < 3; ++$i) {
            if ($priorities) {
                $data = array_merge($data, [
                    'curation_activity_priority_'.($i + 1) => $priorities->get($i) ? $priorities->get($i)->curationActivity->name : null,
                    'curation_group_priority'.($i + 1) => ($priorities->get($i) && $priorities->get($i)->curationGroup) ? $priorities->get($i)->curationGroup->name : null,
                    'priority_'.($i + 1).'_activity_experience' => $priorities->get($i) ? $priorities->get($i)->activity_experience : null,
                    'priority_'.($i + 1).'_activity_experience_details' => ($priorities->get($i) && $priorities->get($i)->activity_experience == 1) ? $priorities->get($i)->activity_experience_details : null,
                    'priority_'.($i + 1).'_effort_experience' => $priorities->get($i) ? $priorities->get($i)->effort_experience : null,
                    'priority_'.($i + 1).'_effort_experience_details' => ($priorities->get($i) && $priorities->get($i)->effort_experience == 1) ? $priorities->get($i)->effort_experience_details : null,
                ]);
            }
        }

        return $data;
    }

    private function getQuestionColumns($questionName, Application $app)
    {
        $data = [];
        foreach ($this->applicationQuestions[$questionName]->getOptions() as $option) {
            $data[$option->label] = '';
            if (is_array($app->{$questionName})) {
                $data[$option->label] = in_array($option->label, $app->{$questionName}) ? 1 : 0;
            }
        }

        return $data;
    }

    private function getReadableResponse($responseValue, $questionDef)
    {
        if (is_null($responseValue)) {
            return '';
        }

        if ($questionDef->hasOptions()) {
            $qName = $questionDef->getName();
            if ($questionDef->numSelectable == 1) {
                return $this->applicationQuestionResponses[$qName][$responseValue] ?? '';
            } else {
                return array_map(
                    function ($responseItem) use ($qName) {
                        return $this->applicationQuestionResponses[$qName][$responseItem] ?? '';
                    },
                    json_decode($responseValue) ?? []
                );
            }
        }

        return $responseValue;
    }

    private function filterByVolunteer($params)
    {
        $params = array_merge($params, ['select' => ['users.id']]);
        $volunteers = $this->volunteerSearchService->search($params);
        $this->query->whereIn('respondent_id', $volunteers->pluck('id'));
    }
}
