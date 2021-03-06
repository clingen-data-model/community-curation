<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCurationActivitiesAddTypeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curation_activities', function (Blueprint $table) {
            $table->unsignedBigInteger('curation_activity_type_id')->nullable()->after('name');
            $table->foreign('curation_activity_type_id')->references('id')->on('curation_activity_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curation_activities', function (Blueprint $table) {
            $table->dropForeign(['curation_activity_type_id']);
        });
    }
}
