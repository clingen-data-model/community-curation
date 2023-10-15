<?php

use App\Aptitude;
use App\VolunteerType;
use Database\Seeders\AptitudesTableSeeder;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (VolunteerType::count() == 0) {
            return;
        }
        $seeder = new AptitudesTableSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Aptitude::withoutGlobalScopes()->find(7)->update(['is_active' => 1, 'is_primary' => 1]);
        Aptitude::find(8)->update(['is_primary' => 0]);
    }
};
