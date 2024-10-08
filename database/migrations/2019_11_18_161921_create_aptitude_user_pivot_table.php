<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAptitudeUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_aptitudes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('aptitude_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('assignment_id')->nullable();
            $table->unsignedBigInteger('attestation_id')->nullable();
            $table->dateTime('trained_at')->nullable();
            $table->dateTime('granted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('aptitude_id')->references('id')->on('aptitudes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_atptiudes');
    }
}
