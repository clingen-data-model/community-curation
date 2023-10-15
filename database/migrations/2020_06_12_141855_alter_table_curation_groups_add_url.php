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
        Schema::table('curation_groups', function (Blueprint $table) {
            $table->string('url')->nullable()->after('working_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('curation_groups', function (Blueprint $table) {
            $table->dropColumn($table);
        });
    }
};
