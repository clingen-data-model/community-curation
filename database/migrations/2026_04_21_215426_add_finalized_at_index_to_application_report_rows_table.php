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
        Schema::table('application_report_rows', function (Blueprint $table) {
            $table->index('finalized_at', 'application_report_rows_finalized_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_report_rows', function (Blueprint $table) {
            $table->dropIndex('application_report_rows_finalized_at_index');
        });
    }
};
