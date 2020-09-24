<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('preferences')) {
            Schema::create('preferences', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->text('description')->nullable();
                $table->enum('data_type', ['boolean', 'integer', 'string', 'float', 'array', 'object'])->default('boolean');
                $table->text('default')->nullable();
                $table->boolean('applies_to_volunteer')->default(1);
                $table->boolean('applies_to_user')->default(0);
                $table->timestamps();
            });
        }

        $seeder = new PreferencesTableSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preferences');
    }
}
