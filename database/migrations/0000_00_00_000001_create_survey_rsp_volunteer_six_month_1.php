<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyRspVolunteerSixMonth1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('rsp_volunteer_six_month_1', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->morphs('respondent');
            $table->integer('survey_id')->unsigned();
            $table->integer('number_of_curation_topics')->nullable();
			$table->integer('number_deferred')->nullable();
			$table->text('why_deferred')->nullable();
			$table->integer('time_spent')->nullable();
			$table->text('enjoying_expert_panel')->nullable();
			$table->integer('plan_to_continue')->nullable();
			$table->text('why_discontinue')->nullable();
			$table->integer('join_addtional_ep')->nullable();
			$table->integer('supported_by_work')->nullable();
			$table->text('other_comments')->nullable();
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
        $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes('rsp_volunteer_six_month_1');
        if(!array_key_exists('rsp_volunteer_six_month_1_respondent_type_respondent_id_index', $indexes)){
            Schema::table('rsp_volunteer_six_month_1', function (Blueprint $table) {
                $table->index(['respondent_type', 'respondent_id']);
            });
        }

        Illuminate\Database\Eloquent\Model::unguard();
        \Sirs\Surveys\Models\Survey::firstOrCreate([
            "name"=>"volunteer_six_month1", 
            "version"=>"1", 
            "file_name"=>"resources/surveys/six_month_volunteer.xml", 
            "response_table"=>"rsp_volunteer_six_month_1"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rsp_volunteer_six_month_1');
        $s = \Sirs\Surveys\Models\Survey::where('name', 'volunteer_six_month1')->where('version', '1');
        $s->delete();
    }
}