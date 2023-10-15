<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('curation_groups', function (Blueprint $table) {
            $table->bigInteger('working_group_id')->unsigned()->nullable()->after('curation_activity_id');
            $table->foreign('working_group_id')->references('id')->on('working_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curation_groups', function (Blueprint $table) {
            $table->dropForeign(['working_group_id']);
            $table->dropColumn('working_group_id');
        });
    }
};
