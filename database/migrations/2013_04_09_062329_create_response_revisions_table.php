<?php

use Illuminate\Database\Migrations\Migration;

class CreateResponseRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('response_revisions', function ($table) {
            $table->increments('id');
            $table->integer('response_id');
            $table->string('response_table');
            $table->integer('user_id')->nullable();
            $table->string('key');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamps();

            $table->index(array('response_id', 'response_table'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('response_revisions');
    }
}
