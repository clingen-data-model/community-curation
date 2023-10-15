<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rsp_application_1', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('respondent_type')->nullable();
            $table->unsignedBigInteger('respondent_id')->nullable();
            $table->integer('survey_id')->unsigned();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('institution')->nullable();
            $table->string('orcid_id')->nullable();
            $table->string('street1')->nullable();
            $table->string('street2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->integer('country_id')->nullable();
            $table->string('email')->nullable();
            $table->string('timezone')->nullable();
            $table->string('hypothesis_id')->nullable();
            $table->integer('highest_ed')->nullable();
            $table->text('highest_ed_other')->nullable();
            $table->string('adv_cert')->nullable();
            $table->integer('self_desc')->nullable();
            $table->text('self_desc_other')->nullable();
            $table->json('race_ethnicity')->nullable();
            $table->text('race_ethnicity_other_detail')->nullable();
            $table->json('ad_campaign')->nullable();
            $table->text('ad_campaign_other_detail')->nullable();
            $table->json('motivation')->nullable();
            $table->text('motivation_other_detail')->nullable();
            $table->json('goals')->nullable();
            $table->text('goals_other_detail')->nullable();
            $table->json('interests')->nullable();
            $table->integer('volunteer_type')->nullable();
            $table->text('notes')->nullable();
            $table->json('imported_survey_data')->nullable();
            $table->integer('curation_activity_1')->nullable();
            $table->integer('panel_1')->nullable();
            $table->integer('effort_experience_1')->nullable();
            $table->text('effort_experience_1_detail')->nullable();
            $table->integer('activity_experience_1')->nullable();
            $table->text('activity_experience_1_detail')->nullable();
            $table->integer('additional_priority')->nullable();
            $table->integer('curation_activity_2')->nullable();
            $table->integer('panel_2')->nullable();
            $table->integer('effort_experience_2')->nullable();
            $table->text('effort_experience_2_detail')->nullable();
            $table->integer('activity_experience_2')->nullable();
            $table->text('activity_experience_2_detail')->nullable();
            $table->integer('curation_activity_3')->nullable();
            $table->integer('panel_3')->nullable();
            $table->integer('effort_experience_3')->nullable();
            $table->text('effort_experience_3_detail')->nullable();
            $table->integer('activity_experience_3')->nullable();
            $table->text('activity_experience_3_detail')->nullable();
            $table->integer('outside_panel')->nullable();
            $table->string('last_page')->nullable();
            $table->integer('duration')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('restrict');
            $table->index(['respondent_type']);
            $table->index(['survey_id']);
            $table->index(['started_at', 'finalized_at', 'survey_id'], 'started_finalized_survey_index');
        });
        $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes('rsp_application_1');
        if (! array_key_exists('rsp_application_1_respondent_type_respondent_id_index', $indexes)) {
            Schema::table('rsp_application_1', function (Blueprint $table) {
                $table->index(['respondent_type', 'respondent_id']);
            });
        }

        Illuminate\Database\Eloquent\Model::unguard();
        \Sirs\Surveys\Models\Survey::firstOrCreate([
            'name' => 'Application1',
            'version' => '1',
            'file_name' => 'resources/surveys/application.xml',
            'response_table' => 'rsp_application_1',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('rsp_application_1');
        $s = \Sirs\Surveys\Models\Survey::where('name', 'Application1')->where('version', '1');
        $s->delete();
    }
};
