<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAptitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aptitudes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('curation_activity_id')->nullable();
            $table->unsignedBigInteger('volunteer_type_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('curation_activity_id')->references('id')->on('curation_activitys');
            $table->foreign('volunteer_type_id')->references('id')->on('volunteer_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('aptitudes');
    }
}
