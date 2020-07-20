<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCurationGroupsAddUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('curation_groups', function (Blueprint $table) {
            $table->string('url')->nullable()->after('working_group_id');
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
            $table->dropColumn($table);
        });
    }
}
