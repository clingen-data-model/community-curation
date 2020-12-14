<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterRspApplications1AddAlreadyMemberQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rsp_application_1', function ($table) {
            $table->integer('already_clingen_member')->nullable()->after('volunteer_type');
            $table->json('already_member_eps')->nullable()->after('already_clingen_member');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rsp_application_1', function ($table) {
            $table->$table->dropColumn('already_clingen_member');
            $table->$table->dropColumn('already_member_eps');
        });
    }
}
