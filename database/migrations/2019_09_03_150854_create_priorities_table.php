<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrioritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('priorities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('priority_order');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('curation_activity_id');
            $table->unsignedBigInteger('curation_group_id')->nullable();
            $table->unsignedInteger('prioritization_round');
            $table->boolean('activity_experience')->default(0);
            $table->text('activity_experience_details')->nullable();
            $table->boolean('effort_experience')->default(0);
            $table->text('effort_experience_details')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onCascade('delete');
            $table->foreign('curation_activity_id')->references('id')->on('curation_activities')->onCascade('delete');
            $table->foreign('curation_group_id')->references('id')->on('curation_groups')->onCascade('delete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('priorities');
    }
}
