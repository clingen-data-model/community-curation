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
            1 => 'Pending Assignment',
            2 => 'Active',
            3 => 'Retired',
            4 => 'Unresponsive'
        ];

        foreach ($statuses as $id => $name) {
            VolunteerStatus::updateOrCreate(compact('id'), compact('id', 'name'));
        }
    }
}
