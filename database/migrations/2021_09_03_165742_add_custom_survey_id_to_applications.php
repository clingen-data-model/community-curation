<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rsp_application_1', function (Blueprint $table) {
            $table->foreignId('custom_survey_id')
                ->nullable()
                ->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rsp_application_1', function (Blueprint $table) {
            $table->dropForeign(['custom_survey_id']);
            $table->dropColumn('custom_survey_id');
        });
    }
};
