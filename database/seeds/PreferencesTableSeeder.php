<?php

namespace Database\Seeders;

use App\Preference;
use Illuminate\Database\Seeder;

class PreferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prefs = [
            [
                'id' => 1,
                'name' => 'hide_demographic_ad',
                'description' => 'Hide info alert that requests demographic info on Volunteer Detail screen if not all demographic info has been entered.',
                'data_type' => 'boolean',
                'default' => null,
                'applies_to_volunteer' => 1,
                'applies_to_user' => 0,
            ],
        ];

        Preference::unguard();
        foreach ($prefs as $def) {
            Preference::updateOrCreate($def);
        }
        Preference::reguard();
    }
}
