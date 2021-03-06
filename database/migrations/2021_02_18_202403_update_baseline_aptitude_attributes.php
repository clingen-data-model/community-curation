<?php

use App\Aptitude;
use App\VolunteerType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Database\Seeders\AptitudesTableSeeder;
use Illuminate\Database\Migrations\Migration;

class UpdateBaselineAptitudeAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (VolunteerType::count() == 0) {
            return;
        }
        $seeder = new AptitudesTableSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Aptitude::withoutGlobalScopes()->find(7)->update(['is_active' => 1, 'is_primary' => 1]);
        Aptitude::find(8)->update(['is_primary' => 0]);
    }
}
