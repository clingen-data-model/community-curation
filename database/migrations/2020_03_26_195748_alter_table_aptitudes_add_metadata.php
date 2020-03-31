<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Aptitudes\Evaluators\BasicAptitudeEvaluator;

class AlterTableAptitudesAddMetadata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aptitudes', function (Blueprint $table) {
            $table->boolean('is_primary')->default(1)->after('volunteer_type_id');
            $table->string('evaluator_class')->after('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aptitudes', function (Blueprint $table) {
            //
        });
    }
}
