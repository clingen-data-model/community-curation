<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmailLogTable extends Migration
{
    public function up()
    {
        Schema::create(config('db_mail_log.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('from');
            $table->json('sender')->nullable();
            $table->json('to');
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->json('reply_to')->nullable();
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection(config('db_email_log.database-connection'))
            ->dropIfExists(config('db_email_log.table_name'));
    }
}
