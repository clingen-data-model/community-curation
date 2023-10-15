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
    public function up(): void
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('topic');
            $table->dateTimeTz('starts_at');
            $table->dateTimeTz('ends_at');
            $table->string('url');
            $table->text('invite_message')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
