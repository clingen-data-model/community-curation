<?php

namespace App\Actions\Reports;

use App\Country;
use App\Application;
use App\ApplicationReportRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ApplicationReportRowCreate
{
  private array $questionDefs;

  public function __construct()
  {
    $survey = Cache::remember('survey_application1', 60*10, function () {
      return class_survey()::findBySlug('application1');
    });
    $this->questionDefs = $survey->getQuestions();
  }

  public function handle(Application $application)
  {
      foreach ($this->questionDefs as $definition) {
          $qName = $definition->getName();
          $response = $this->getReadableResponse($application->{$qName}, $definition);
          $application->{$qName} = $response ? $response : '';
      }

      $snapshot = $this->makeSnapshot($application);

      $data = [
        'application_id' => $application->id,
        'application_type' => get_class($application),
        'user_id' => $application->respondent_id,
        'finalized_at' => $application->finalized_at,
        'version' => 1,
        'data' => $snapshot->toArray(),
      ];

      ApplicationReportRow::updateOrCreate(['application_type'=>get_class($application), 'application_id' => $application->id], $data);

      return $snapshot;
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

  private function makeSnapshot($app):Collection
  {
    $identifiers  = collect([
      'volunteer_id' => $app->respondent_id,
      'first_name' => $app->first_name,
      'last_name' => $app->last_name,
      'email' => $app->email,
    ]);

    $metadata = collect([
      'date_completed' => $app->finalized_at,
      'imported_from_google_sheets' => !is_null($app->imported_survey_data) ? 'Yes' : 'No',
    ]);

    return collect([
      'personal' => $identifiers->merge(collect([
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
      ]))->merge($metadata),
      'professional' => $identifiers->merge(collect([
        'volunteer_id' => $app->respondent_id,
        'heighest_ed' => ($app->highest_ed) ? $app->highest_ed : '',
        'heighest_ed_other' => $app->highest_ed_other,
        'advanced_certification' => $app->adv_cert,
        'self_description' => $app->self_desc ? $app->self_desc : '',
        'self_description_other' => $app->self_desc_other,
        'already_clingen_member' => $app->respondent->already_clingen_member,
        'already_member_cgs' => $app->respondent->memberGroups->pluck('name')->join(', ')
      ]))->merge($metadata),
      'demographic' => $identifiers->merge(collect($this->getQuestionColumns('race_ethnicity', $app))->merge(['other' => $app->race_ethnicity_other_detail]))->merge($metadata),
      'outreach' => $identifiers->merge(collect($this->getQuestionColumns('ad_campaign', $app))->merge(['other' => $app->ad_campaign_other]))->merge($metadata),
      'motivation' => $identifiers->merge(collect($this->getQuestionColumns('motivation', $app))->merge(['other' => $app->motivation_other]))->merge($metadata),
      'goals' => $identifiers->merge(collect($this->getQuestionColumns('goals', $app))->merge(['other' => $app->goals_other]))->merge($metadata),
      'interests' => $identifiers->merge(collect($this->getQuestionColumns('interests', $app)))->merge($metadata),
      'ccdb' => $identifiers->merge(collect(['baseline/comprehensive' => $app->volunteer_type,])->merge($this->getPriorityData($app)))->merge($metadata),
    ]);
  }

  private function getPriorityData($app)
  {
      $data = [];
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
      foreach ($this->questionDefs[$questionName]->getOptions() as $option) {
          $data[$option->label] = '';
          if (is_array($app->{$questionName})) {
              $data[$option->label] = in_array($option->label, $app->{$questionName}) ? 1 : 0;
          }
      }

      return $data;
  }

}