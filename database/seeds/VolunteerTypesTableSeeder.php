<?php

namespace Database\Seeders;

use App\VolunteerType;
use Illuminate\Database\Seeder;

class VolunteerTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        VolunteerType::unguard();
        foreach (config('volunteers.types') as $name => $id) {
            VolunteerType::updateOrCreate(
                compact('id'),
                compact('id', 'name')
            );
        }
        VolunteerType::reguard();
    }
}
