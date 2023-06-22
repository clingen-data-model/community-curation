<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsp_volunteer_three_month_1', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedBigInteger('respondent_id');
            $table->string('respondent_type');
            $table->integer('survey_id')->unsigned();
            $table->json('curation_effort')->nullable();
            $table->json('curation_groups')->nullable();
            $table->integer('highest_ed')->nullable();
            $table->text('highest_ed_other')->nullable();
            $table->integer('satisfaction')->nullable();
            $table->text('dissatified_feedback')->nullable();
            $table->integer('training_clear')->nullable();
            $table->text('training_clear_details')->nullable();
            $table->integer('training_sufficient')->nullable();
            $table->text('training_sufficient_details')->nullable();
            $table->integer('seek_adtl_trng')->nullable();
            $table->text('seek_adtl_trng_details')->nullable();
            $table->integer('rcmd_atdl_trng')->nullable();
            $table->text('rcmd_adtl_trng_detail')->nullable();
            $table->integer('assigned_curation')->nullable();
            $table->integer('hours_spent')->nullable();
            $table->text('feedback_helpful')->nullable();
            $table->integer('plan_to_continue')->nullable();
            $table->text('plan_to_continue_details')->nullable();
            $table->integer('transfer')->nullable();
            $table->integer('join_adtl')->nullable();
            $table->integer('recommend')->nullable();
            $table->text('other_feedback')->nullable();
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
        $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes('rsp_volunteer_three_month_1');
        if (! array_key_exists('rsp_volunteer_three_month_1_respondent_type_respondent_id_index', $indexes)) {
            Schema::table('rsp_volunteer_three_month_1', function (Blueprint $table) {
                $table->index(['respondent_type', 'respondent_id']);
            });
        }

        Illuminate\Database\Eloquent\Model::unguard();
        \Sirs\Surveys\Models\Survey::firstOrCreate([
            'name' => 'volunteer_three_month1',
            'version' => '1',
            'file_name' => 'resources/surveys/three_month_volunteer_followup.xml',
            'response_table' => 'rsp_volunteer_three_month_1',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rsp_volunteer_three_month_1');
        $s = \Sirs\Surveys\Models\Survey::where('name', 'volunteer_three_month1')->where('version', '1');
        $s->delete();
    }
};
