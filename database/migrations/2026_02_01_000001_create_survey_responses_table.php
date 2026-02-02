<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyResponsesTable extends Migration
{
    public function up()
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('survey_slug')->index();
            $table->unsignedBigInteger('respondent_id');
            $table->string('respondent_type');
            $table->json('response_data');
            $table->string('last_page')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['respondent_type', 'respondent_id']);
            $table->index(['started_at', 'finalized_at', 'survey_slug']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_responses');
    }
}
