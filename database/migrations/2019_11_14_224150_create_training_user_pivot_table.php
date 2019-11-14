<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('training_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('training_id', 'training_id_foreign')->references('id')->on('trainings')->onDelete('cascade');
            $table->foreign('user_id', 'user_id_foreign')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_user');
    }
}
