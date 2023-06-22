<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curation_activity_user', function (Blueprint $table) {
            $table->bigInteger('curation_activity_id')->unsigned()->index();
            $table->foreign('curation_activity_id', 'causer_curation_activity_id_foreign')->references('id')->on('curation_activities')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id', 'causer_user_id_foreign')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['curation_activity_id', 'user_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curation_activity_user');
    }
};
