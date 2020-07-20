<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCurationGroupsAddWorkingGroupId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curation_groups', function (Blueprint $table) {
            $table->bigInteger('working_group_id')->unsigned()->nullable()->after('curation_activity_id');
            $table->foreign('working_group_id')->references('id')->on('working_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('curation_groups', function (Blueprint $table) {
            $table->dropForeign(['working_group_id']);
            $table->dropColumn('working_group_id');
        });
    }
}
