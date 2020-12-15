<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUsersAddAlreadyMemberColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('already_clingen_member')->nullable()->default(0)->after('volunteer_status_id');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('already_clingen_member');
            $table->dropColumn('already_member_eps');
        });
    }
}
