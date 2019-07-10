<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveysTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('version');
            $table->string('slug')->unique();
            $table->string('file_name')->unique();
            $table->string('response_table')->unique();
            $table->timestamps();

            $table->unique(['name', 'version']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('surveys');
    }
}
