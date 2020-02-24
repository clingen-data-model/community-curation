<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyRspThreeMonthVolunteerFollowup1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsp_threemonthvolunteerfollowup_1', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedBigInteger('respondent_id');
            $table->string('respondent_type');
            $table->integer('survey_id')->unsigned();
            $table->tinyInteger('Actionability')->nullable();
            $table->tinyInteger('Dosage')->nullable();
            $table->tinyInteger('Gene')->nullable();
            $table->tinyInteger('Somatic_Variant')->nullable();
            $table->tinyInteger('Variant')->nullable();
            $table->tinyInteger('Intellectual_Disability_and_Autism_GCEP')->nullable();
            $table->tinyInteger('Brain_Malformations_GCEP')->nullable();
            $table->tinyInteger('Hereditary_Cancer_GCEP')->nullable();
            $table->tinyInteger('Hemostasis/_Thrombosis_GCEP')->nullable();
            $table->tinyInteger('Mitochondrial_Diseases_GCEP')->nullable();
            $table->tinyInteger('Monogenic_Diabetes_GCEP')->nullable();
            $table->tinyInteger('Congenital_Myopathies_GCEP')->nullable();
            $table->tinyInteger('Charcot_Marie_Tooth_GCEP')->nullable();
            $table->tinyInteger('Limb_Girdle_Muscular_Dystrophy_GCEP')->nullable();
            $table->tinyInteger('RASopathy_VCEP')->nullable();
            $table->tinyInteger('FBN1_VCEP')->nullable();
            $table->tinyInteger('Cardiomyopathy_VCEP')->nullable();
            $table->tinyInteger('Familial_Hypercholesterolemia_VCEP')->nullable();
            $table->tinyInteger('PTEN_VCEP')->nullable();
            $table->tinyInteger('Myeloid_Malignancy_VCEP')->nullable();
            $table->tinyInteger('VHL_VCEP')->nullable();
            $table->tinyInteger('TP53_VCEP')->nullable();
            $table->tinyInteger('CDH1_VCEP')->nullable();
            $table->tinyInteger('DICER1_VCEP')->nullable();
            $table->tinyInteger('Colorectal_Cancer_VCEP')->nullable();
            $table->tinyInteger('ACADVL_VCEP')->nullable();
            $table->tinyInteger('Lysosomal_Disorders_')->nullable();
            $table->tinyInteger('Monogenic_Diabetes_VCEP')->nullable();
            $table->tinyInteger('Cerebral_Creatine_Deficiency_Syndrome_VCEP')->nullable();
            $table->tinyInteger('Phenylketonuria_VCEP')->nullable();
            $table->tinyInteger('Peroxisomal_Disorders_VCEP')->nullable();
            $table->tinyInteger('OTC_VCEP')->nullable();
            $table->tinyInteger('Hearing_Loss_VCEP')->nullable();
            $table->tinyInteger('Coagulation_Factor_Deficiencies_VCEP')->nullable();
            $table->tinyInteger('Platelet_Disorders_VCEP')->nullable();
            $table->tinyInteger('Brain_Malformations_VCEP')->nullable();
            $table->tinyInteger('Limb_Girdle_Muscular_dystrophy_VCEP')->nullable();
            $table->tinyInteger('Congenital_Myopathies_VCEP')->nullable();
            $table->tinyInteger('Dosage-Recurrent_Regions')->nullable();
            $table->tinyInteger('Dosage-Neurodevelopmental')->nullable();
            $table->tinyInteger('Dosage-Hereditary_Cancer')->nullable();
            $table->tinyInteger('Somatic-Pediatric')->nullable();
            $table->tinyInteger('Somatic-Pancreatic')->nullable();
            $table->tinyInteger('Somatic-Genitourinary_Tract_Cancer')->nullable();
            $table->tinyInteger('Actionability-Pediatric')->nullable();
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
        $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes('rsp_threemonthvolunteerfollowup_1');
        if (!array_key_exists('volunteer_threemonth_respondent', $indexes)) {
            Schema::table('rsp_threemonthvolunteerfollowup_1', function (Blueprint $table) {
                $table->index(['respondent_type', 'respondent_id'], 'volunteer_threemonth_respondent');
            });
        }

        Illuminate\Database\Eloquent\Model::unguard();
        \Sirs\Surveys\Models\Survey::firstOrCreate([
            "name"=>"ThreeMonthVolunteerFollowup1",
            "version"=>"1",
            "file_name"=>"resources/surveys/three_month_volunteer_followup.xml",
            "response_table"=>"rsp_threemonthvolunteerfollowup_1"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rsp_threemonthvolunteerfollowup_1');
        $s = \Sirs\Surveys\Models\Survey::where('name', 'ThreeMonthVolunteerFollowup1')->where('version', '1');
        $s->delete();
    }
}
