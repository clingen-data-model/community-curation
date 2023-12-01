<?php

use App\UserAptitude;
use Illuminate\Database\Migrations\Migration;

class UpdateBaselineUserAptitudes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        UserAptitude::withTrashed()->where('aptitude_id', 7)
            ->each(function ($ua) {
                $ua->update(['aptitude_id' => 8]);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
