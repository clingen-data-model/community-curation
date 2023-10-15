<?php

use App\UserAptitude;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        UserAptitude::withTrashed()->where('aptitude_id', 7)
            ->each(function ($ua) {
                $ua->update(['aptitude_id' => 8]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
