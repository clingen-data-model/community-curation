<?php

namespace App\Services\Reports;

use App\Application;
use App\Contracts\ReportGenerator;
use Illuminate\Support\Collection;
use App\Services\Search\VolunteerSearchService;

class ApplicationReportGenerator implements ReportGenerator
{
    protected $applicationQuestions;
    protected $volunteerSearchService;
    protected $query;

    public function __construct(VolunteerSearchService $volunteerSearchService)
    {
        $this->volunteerSearchService = $volunteerSearchService;
        $this->query = Application::query()->finalized();
        $this->applicationQuestions = class_survey()::findBySlug('application1')->getQuestions();
    }

    public function generate($filterParams = []):Collection
    {
        if ($filterParams) {
            $this->filterByVolunteer($filterParams);
        }

        $applications = $this->query->get();

        $questionDefs = class_survey()::findBySlug('application1')->getQuestions();
        $rawData = $applications->map(function ($application) use ($questionDefs) {
            foreach ($questionDefs as $definition) {
                $qName = $definition->getName();
                $response = $this->getReadableResponse($application->{$qName}, $definition);
                $application->{$qName} = $response ? $response : '';
            }
            return $application;
        });

        $tidyData = $this->tidyUpData($rawData);
        $data = collect();
        if ($tidyData->count() > 0) {
            foreach (array_keys($tidyData->first()) as $group) {
                $data->put($group, $tidyData->pluck($group));
            }
        }

        return $data;
    }

    public function addConstraint(callable $func)
    {
        $func($this->query);

        return $this;
    }

    private function tidyUpData(Collection $data): Collection
    {
        return $data->map(function ($app) {
            $introColumns = collect([
                'volunteer_id' => $app->respondent_id,
                'first_name' => $app->first_name,
                'last_name' => $app->last_name,
                'email' => $app->email,
            ]);
            $outroColumns = collect([
                'date_completed' => $app->finalized_at,
                'imported_from_google_sheets' => !is_null($app->imported_survey_data) ? 'Yes' : 'No'
    
            ]);
            return [
                'personal' => $introColumns->merge(
                    [
                        'institution' => $app->institution,
                        'orcid_id' => $app->orcid_id,
                        'hypothesis_id' => $app->hypothesis_id,
                        'street1' => $app->street1,
                        'street2' => $app->street2,
                        'city' => $app->city,
                        'state' => $app->state,
                        'zip' => $app->zip,
                        'country_id' => $app->country_id,
                        'timezone' => $app->timezone,
                    ],
                    $outroColumns
                ),
                'professional' => $introColumns->merge(
                    [
                        'volunteer_id' => $app->respondent_id,
                        'heighest_ed' => ($app->highest_ed) ? $app->highest_ed : '',
                        'heights_ed_other' => $app->highest_ed_other,
                        'advanced_certification' => $app->adv_cert,
                        'self_description' => $app->self_desc ? $app->self_desc : '',
                        'self_description_other' => $app->self_desc_other,
                    ],
                    $outroColumns
                ),
                'demographic' => $introColumns->merge(
                    $this->getQuestionColumns('race_ethnicity', $app),
                    $outroColumns
                ),
                'outreach' => $introColumns->merge(
                    $this->getQuestionColumns('ad_campaign', $app),
                    ['other' => $app->ad_campaign_other],
                    $outroColumns
                ),
                'motivation' => $introColumns->merge(
                    $this->getQuestionColumns('motivation', $app),
                    ['other' => $app->motivationother],
                    $outroColumns
                ),
                'goals' => $introColumns->merge(
                    $this->getQuestionColumns('goals', $app),
                    ['other' => $app->goals_other],
                    $outroColumns
                ),
                'interestes' => $introColumns->merge(
                    $this->getQuestionColumns('interests', $app),
                    $outroColumns
                )
            ];
        });
    }

    private function getQuestionColumns($questionName, Application $app)
    {
        $data = [];
        foreach ($this->applicationQuestions[$questionName]->getOptions() as $option) {
            $data[$option->label] = '';
            if (is_array($app->{$questionName})) {
                $data[$option->label] = in_array($option->label, $app->{$questionName}) ? 1: 0;
            }
        }

        return $data;
    }


    private function getReadableResponse($responseValue, $questionDef)
    {
        $readableValue = $responseValue;
        if ($questionDef->hasOptions()) {
            $readableValue = array_map(
                function ($optionBlock) {
                    return $optionBlock->label;
                },
                $questionDef->getOptionsForResponseValue($responseValue)
            );
            if ($questionDef->numSelectable == 1) {
                $readableValue = isset($readableValue[0]) ? $readableValue[0] : '';
            }
        }

        return $readableValue;
    }

    private function filterByVolunteer($params)
    {
        $params = array_merge($params, ['select' => ['users.id']]);
        $volunteers = $this->volunteerSearchService->search($params);
        $this->query->whereIn('respondent_id', $volunteers->pluck('id'));
    }
}