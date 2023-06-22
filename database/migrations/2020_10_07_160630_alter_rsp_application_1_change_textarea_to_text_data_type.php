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
    public function up()
    {
        Schema::table('rsp_application_1', function (Blueprint $table) {
            $table->text('adv_cert')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rsp_application_1', function (Blueprint $table) {
            $table->string('adv_cert')->nullable()->change();
        });
    }
};
