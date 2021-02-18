<?php

use App\Aptitude;
use Database\Seeders\AptitudesTableSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBaselineAptitudeAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
        Aptitude::find(7)->update(['is_active' => 1, 'is_primary' => 1]);
        Aptitude::find(8)->update(['is_primary' => 0]);
    }
}
