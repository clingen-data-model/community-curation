<?php

namespace Database\Seeders;

use App\VolunteerStatus;
use Illuminate\Database\Seeder;

class VolunteerStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (config('volunteers.statuses') as $name => $id) {
            VolunteerStatus::updateOrCreate(compact('id'), compact('id', 'name'));
        }
    }
}
