<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyRspPriorities1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('rsp_priorities_1', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->morphs('respondent');
            $table->integer('survey_id')->unsigned();
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
            $table->index(['started_at', 'finalized_at', 'survey_id'], 'started_finalized_priorities_index');
        });
        $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes('rsp_priorities_1');
        if(!array_key_exists('rsp_priorities_1_respondent_type_respondent_id_index', $indexes)){
            Schema::table('rsp_priorities_1', function (Blueprint $table) {
                $table->index(['respondent_type', 'respondent_id']);
            });
        }

        Illuminate\Database\Eloquent\Model::unguard();
        \Sirs\Surveys\Models\Survey::firstOrCreate([
            "name"=>"priorities1", 
            "version"=>"1", 
            "file_name"=>"resources/surveys/priorities.xml", 
            "response_table"=>"rsp_priorities_1"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rsp_priorities_1');
        $s = \Sirs\Surveys\Models\Survey::where('name', 'priorities1')->where('version', '1');
        $s->delete();
    }
}