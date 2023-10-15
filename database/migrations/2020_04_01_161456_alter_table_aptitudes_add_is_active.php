<?php

use App\Aptitude;
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
            $table->boolean('is_active')->default(1)->after('is_primary');
        });
        if (Aptitude::count() > 0) {
            $seeder = new AptitudesTableSeeder();
            $seeder->run();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aptitudes', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
