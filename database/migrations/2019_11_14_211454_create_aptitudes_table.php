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
        Schema::create('aptitudes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('subject_type')->nullable();
            $table->string('subject_id')->nullable();
            $table->unsignedBigInteger('volunteer_type_id');
            $table->string('training_materials_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('volunteer_type_id')->references('id')->on('volunteer_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aptitudes');
    }
};
