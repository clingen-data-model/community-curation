<?php

namespace Database\Seeders;

use App\Volunteer;
use Illuminate\Database\Seeder;

class VolunteersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Volunteer::class, 10)->create();
    }
}
