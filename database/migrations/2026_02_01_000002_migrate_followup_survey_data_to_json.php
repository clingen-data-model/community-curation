<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateFollowupSurveyDataToJson extends Migration
{
    private $threeMonthDataColumns = [
        'curation_effort',
        'curation_groups',
        'highest_ed',
        'highest_ed_other',
        'satisfaction',
        'dissatified_feedback',
        'training_clear',
        'training_clear_details',
        'training_sufficient',
        'training_sufficient_details',
        'seek_adtl_trng',
        'seek_adtl_trng_details',
        'rcmd_atdl_trng',
        'rcmd_adtl_trng_detail',
        'assigned_curation',
        'hours_spent',
        'feedback_helpful',
        'plan_to_continue',
        'plan_to_continue_details',
        'transfer',
        'join_adtl',
        'recommend',
        'other_feedback',
    ];

    private $sixMonthDataColumns = [
        'number_of_curation_topics',
        'number_deferred',
        'why_deferred',
        'time_spent',
        'enjoying_curation_group',
        'plan_to_continue',
        'why_discontinue',
        'join_addtional_ep',
        'supported_by_work',
        'other_comments',
    ];

    public function up()
    {
        $this->migrateTable(
            'rsp_volunteer_three_month_1',
            'volunteer-three-month1',
            $this->threeMonthDataColumns
        );

        $this->migrateTable(
            'rsp_volunteer_six_month_1',
            'volunteer-six-month1',
            $this->sixMonthDataColumns
        );
    }

    public function down()
    {
        DB::table('survey_responses')
            ->whereIn('survey_slug', ['volunteer-three-month1', 'volunteer-six-month1'])
            ->delete();
    }

    private function migrateTable($sourceTable, $surveySlug, $dataColumns)
    {
        if (!Schema::hasTable($sourceTable)) {
            return;
        }

        $rows = DB::table($sourceTable)->get();

        foreach ($rows as $row) {
            $responseData = [];
            foreach ($dataColumns as $col) {
                $value = $row->$col ?? null;

                if (!is_null($value) && in_array($col, ['curation_effort', 'curation_groups'])) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $value = $decoded;
                    }
                }

                $responseData[$col] = $value;
            }

            DB::table('survey_responses')->insert([
                'survey_slug' => $surveySlug,
                'respondent_id' => $row->respondent_id,
                'respondent_type' => $row->respondent_type,
                'response_data' => json_encode($responseData),
                'last_page' => $row->last_page,
                'started_at' => $row->started_at,
                'finalized_at' => $row->finalized_at,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
                'deleted_at' => $row->deleted_at,
            ]);
        }
    }
}
