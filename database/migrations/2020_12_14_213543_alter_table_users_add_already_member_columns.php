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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('already_clingen_member')->nullable()->default(0)->after('volunteer_status_id');
            $table->json('already_member_cgs')->nullable()->after('already_clingen_member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('already_clingen_member');
            $table->dropColumn('already_member_cgs');
        });
    }
};
