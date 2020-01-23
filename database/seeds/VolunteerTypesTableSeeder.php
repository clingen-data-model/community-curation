<?php

use Illuminate\Database\Seeder;
use App\VolunteerType;

class VolunteerTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VolunteerType::unguard();
        foreach(config('project.volunteer_types') as $id => $name) {
            VolunteerType::updateOrCreate(
                compact('id'),
                compact('id', 'name')
            );
        }
        VolunteerType::reguard();
    }
}
