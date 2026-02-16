<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateApplicationSurveyDataToJson extends Migration
{
    private $applicationDataColumns = [
        'first_name',
        'last_name',
        'institution',
        'orcid_id',
        'street1',
        'street2',
        'city',
        'state',
        'zip',
        'country_id',
        'email',
        'timezone',
        'hypothesis_id',
        'already_clingen_member',
        'already_member_cgs',
        'highest_ed',
        'highest_ed_other',
        'adv_cert',
        'self_desc',
        'self_desc_other',
        'race_ethnicity',
        'race_ethnicity_other_detail',
        'ad_campaign',
        'ad_campaign_other_detail',
        'motivation',
        'motivation_other_detail',
        'goals',
        'goals_other_detail',
        'interests',
        'volunteer_type',
        'notes',
        'imported_survey_data',
        'curation_activity_1',
        'panel_1',
        'effort_experience_1',
        'effort_experience_1_detail',
        'activity_experience_1',
        'activity_experience_1_detail',
        'additional_priority',
        'curation_activity_2',
        'panel_2',
        'effort_experience_2',
        'effort_experience_2_detail',
        'activity_experience_2',
        'activity_experience_2_detail',
        'curation_activity_3',
        'panel_3',
        'effort_experience_3',
        'effort_experience_3_detail',
        'activity_experience_3',
        'activity_experience_3_detail',
        'outside_panel',
    ];

    private $applicationJsonColumns = [
        'already_member_cgs',
        'race_ethnicity',
        'ad_campaign',
        'motivation',
        'goals',
        'interests',
        'imported_survey_data',
    ];

    private $prioritiesDataColumns = [
        'curation_activity_1',
        'panel_1',
        'effort_experience_1',
        'effort_experience_1_detail',
        'activity_experience_1',
        'activity_experience_1_detail',
        'additional_priority',
        'curation_activity_2',
        'panel_2',
        'effort_experience_2',
        'effort_experience_2_detail',
        'activity_experience_2',
        'activity_experience_2_detail',
        'curation_activity_3',
        'panel_3',
        'effort_experience_3',
        'effort_experience_3_detail',
        'activity_experience_3',
        'activity_experience_3_detail',
        'outside_panel',
    ];

    public function up()
    {
        $this->migrateTable(
            'rsp_application_1',
            'application1',
            $this->applicationDataColumns,
            $this->applicationJsonColumns
        );

        $this->migrateTable(
            'rsp_priorities_1',
            'priorities1',
            $this->prioritiesDataColumns,
            []
        );
    }

    public function down()
    {
        DB::table('survey_responses')
            ->whereIn('survey_slug', ['application1', 'priorities1'])
            ->delete();
    }

    private function migrateTable($sourceTable, $surveySlug, $dataColumns, $jsonColumns)
    {
        if (!Schema::hasTable($sourceTable)) {
            return;
        }

        $rows = DB::table($sourceTable)->get();

        foreach ($rows as $row) {
            $responseData = [];
            foreach ($dataColumns as $col) {
                $value = $row->$col ?? null;

                if (!is_null($value) && in_array($col, $jsonColumns)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $value = $decoded;
                    }
                }

                $responseData[$col] = $value;
            }

            DB::table('survey_responses')->insert([
                'survey_slug' => $surveySlug,
                'respondent_id' => $row->respondent_id ?? 0,
                'respondent_type' => $row->respondent_type ?? 'App\User',
                'response_data' => json_encode($responseData),
                'last_page' => $row->last_page,
                'started_at' => $row->started_at,
                'finalized_at' => $row->finalized_at,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
                'deleted_at' => $row->deleted_at ?? null,
            ]);
        }
    }
}
