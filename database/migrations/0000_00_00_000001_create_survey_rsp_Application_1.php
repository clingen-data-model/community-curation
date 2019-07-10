<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyRspApplication1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('rsp_application_1', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('respondent_type')->nullable();
            $table->integer('respondent_id')->nullable();
            $table->integer('survey_id')->unsigned();
            $table->string('applicant_name')->nullable();
			$table->string('institution')->nullable();
			$table->string('address')->nullable();
			$table->string('email')->nullable();
			$table->integer('timezone')->nullable();
			$table->integer('highest_ed')->nullable();
			$table->string('highest_ed_other')->nullable();
			$table->string('adv_cert')->nullable();
			$table->integer('self_desc')->nullable();
			$table->string('self_desc_other')->nullable();
			$table->json('ad_campaign')->nullable();
			$table->string('ad_campaign_other_detail')->nullable();
			$table->json('motivation')->nullable();
			$table->string('motivation_other_detail')->nullable();
			$table->json('goals')->nullable();
			$table->string('goals_other_detail')->nullable();
			$table->json('interests')->nullable();
			$table->integer('volunteer_type')->nullable();
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
        if(!array_key_exists('rsp_application_1_respondent_type_respondent_id_index', $indexes)){
            Schema::table('rsp_application_1', function (Blueprint $table) {
                $table->index(['respondent_type', 'respondent_id']);
            });
        }

        Illuminate\Database\Eloquent\Model::unguard();
        \Sirs\Surveys\Models\Survey::firstOrCreate([
            "name"=>"Application1", 
            "version"=>"1", 
            "file_name"=>"resources/surveys/application.xml", 
            "response_table"=>"rsp_application_1"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rsp_application_1');
        $s = \Sirs\Surveys\Models\Survey::where('name', 'Application1')->where('version', '1');
        $s->delete();
    }
}