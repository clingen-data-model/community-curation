<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAptitudesAddIsActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aptitudes', function (Blueprint $table) {
            $table->boolean('is_active')->default(1)->after('is_primary');
        });
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
        Schema::table('aptitudes', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}
