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
        Schema::table('uploads', function (Blueprint $table) {
            $table->unsignedBigInteger('upload_category_id')->nullable()->after('user_id');

            $table->foreign('upload_category_id')->references('id')->on('upload_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uploads', function (Blueprint $table) {
            $table->dropForeign(['upload_category_id']);
            $table->dropColumn('upload_category_id');
        });
    }
};
