<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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
     */
    public function down(): void
    {
        Schema::dropIfExists('user_atptiudes');
    }
};
