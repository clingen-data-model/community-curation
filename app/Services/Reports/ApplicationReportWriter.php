<?php

namespace App\Services\Reports;

use App\Application;
use App\Contracts\ReportWriter;
use Illuminate\Support\Collection;
use Box\Spout\Writer\XLSX\Writer as XlsxWriter;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

class ApplicationReportWriter extends AbstractReportWriter implements ReportWriter
{
    protected $writer;
    protected $applicationQuestions;

    public function __construct(XlsxWriter $writer)
    {
        $this->writer = $writer;

        $this->applicationQuestions = class_survey()::findBySlug('application1')->getQuestions();
    }

    public function writeData(Collection $data)
    {
        $tidyData = $this->tidyUpData($data);
        $sheetNames = array_keys($tidyData->first());
        foreach ($sheetNames as $idx => $sheetName) {
            $sheetData = $tidyData->pluck($sheetName);
            $sheet = $this->getCurrentSheet();
            $sheet->setName($sheetName);

            $this->getWriter()->addRow($this->buildHeader($sheetData), (new StyleBuilder())->setFontBold()->build());
            
            foreach ($sheetData->toArray() as $rowData) {
                $row = $this->createRow($rowData);
                $this->getWriter()->addRow($row);
            }

            if ($idx+1 < count($sheetNames)) {
                $this->addNewSheetAndMakeItCurrent();
            }
        }

        $this->getWriter()->close();
    }

    private function tidyUpData(Collection $data): Collection
    {
        return $data->map(function ($app) {
            return [
                'personal' => collect([
                    'volunteer_id' => $app->respondent_id,
                    'first_name' => $app->first_name,
                    'last_name' => $app->last_name,
                    'email' => $app->email,
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
                ]),
                'professional' => collect([
                    'volunteer_id' => $app->respondent_id,
                    'heighest_ed' => ($app->highest_ed) ? $app->highest_ed : '',
                    'heights_ed_other' => $app->highest_ed_other,
                    'advanced_certification' => $app->adv_cert,
                    'self_description' => $app->self_desc ? $app->self_desc : '',
                    'self_description_other' => $app->self_desc_other,
                ]),
                'demographic' => collect(array_merge(
                    ['volunteer_id' => $app->respondent_id],
                    $this->getQuestionColumns('race_ethnicity', $app)
                                )),
                'outreach' => collect(array_merge(
                    ['volunteer_id' => $app->respondent_id],
                    $this->getQuestionColumns('ad_campaign', $app),
                    ['other' => $app->ad_campaign_other]
                                )),
                'motivation' => collect(array_merge(
                    ['volunteer_id' => $app->respondent_id],
                    $this->getQuestionColumns('motivation', $app),
                    ['other' => $app->motivationother]
                                )),
                'goals' => collect(array_merge(
                    ['volunteer_id' => $app->respondent_id],
                    $this->getQuestionColumns('goals', $app),
                    ['other' => $app->goals_other]
                                )),
                'interestes' => collect(array_merge(
                    ['volunteer_id' => $app->respondent_id],
                    $this->getQuestionColumns('interests', $app)
                                )),
                'metdata' => collect([
                    'volunteer_id' => $app->respondent_id,
                    'date_completed' => $app->finalized_at,
                    'imported_from_google_sheets' => !is_null($app->imported_survey_data) ? 'Yes' : 'No'
                ])
            ];
        });
    }

    private function getQuestionColumns($questionName, Application $app)
    {
        $data = [];
        foreach ($this->applicationQuestions[$questionName]->getOptions() as $option) {
            $data[$option->label] = '';
            if (is_array($app->{$questionName})) {
                $data[$option->label] = in_array($option->label, $app->{$questionName}) ? '1': '0';
            }
        }

        return $data;
    }
}
