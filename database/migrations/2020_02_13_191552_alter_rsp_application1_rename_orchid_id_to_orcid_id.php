<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRspApplication1RenameOrchidIdToOrcidId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('rsp_application_1', 'orchid_id')) {
            Schema::table('rsp_application_1', function (Blueprint $table) {
                $table->renameColumn('orchid_id', 'orcid_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('rsp_application_1', 'orcid_id')) {
            Schema::table('rsp_application_1', function (Blueprint $table) {
                $table->renameColumn('orcid_id', 'orchid_id');
            });
        }
    }
}
