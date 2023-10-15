<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rsp_application_1', function ($table) {
            $table->integer('already_clingen_member')->nullable()->after('volunteer_type');
            $table->json('already_member_cgs')->nullable()->after('already_clingen_member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rsp_application_1', function ($table) {
            $table->$table->dropColumn('already_clingen_member');
            $table->$table->dropColumn('already_member_cgs');
        });
    }
};
