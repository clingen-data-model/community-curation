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
        $statuses = [
            1 => 'Applied',
            2 => 'Active',
            3 => 'Retired',
            4 => 'Unresponsive',
            4 => 'Declined'
        ];

        foreach ($statuses as $id => $name) {
            VolunteerStatus::updateOrCreate(compact('id'), compact('id', 'name'));
        }
    }
}
