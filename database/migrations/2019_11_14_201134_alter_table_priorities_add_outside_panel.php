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
    public function up(): void
    {
        Schema::table('priorities', function (Blueprint $table) {
            $table->integer('outside_panel')->nullable()->after('effort_experience_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('priorities', function (Blueprint $table) {
            $table->dropColumn('outside_panel');
        });
    }
};
