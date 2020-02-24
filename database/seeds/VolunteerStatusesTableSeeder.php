<?php

use Illuminate\Database\Seeder;
use App\VolunteerStatus;

class VolunteerStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('volunteers.statuses') as $name => $id) {
            VolunteerStatus::updateOrCreate(compact('id'), compact('id', 'name'));
        }
    }
}
