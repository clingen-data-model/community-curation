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
        Schema::table('aptitudes', function (Blueprint $table) {
            $table->boolean('is_primary')->default(1)->after('volunteer_type_id');
            $table->string('evaluator_class')->after('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aptitudes', function (Blueprint $table) {
            //
        });
    }
};
