<?php

use Illuminate\Database\Seeder;
use App\SelfDescription;

class SelfDescriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            1 => 'Clinical Medical Geneticist',
            2 => 'Physician (Non-geneticist)',
            3 => 'Clinical laboratory geneticist',
            4 => 'Genetic counselor',
            5 => 'Post Doc/Resident/Fellow (MD and/or PhD)',
            6 => 'Scientific Researcher',
            7 => 'Biocurator',
            8 => 'Variant Analyst/Scientist - Industry',
            9 => 'Variant Analyst/Scientist - Academic',
            10 => 'Graduate Student',
            11 => 'Undergraduate Student',
            12 => 'High School Student',
            13 => 'Citizen Scientist/Patient Advocate',
            100 => 'Other',
        ];

        SelfDescription::unguard();
        foreach ($items as $id => $name) {
            SelfDescription::firstOrCreate(['id' => $id], compact('id', 'name'));
        }
        SelfDescription::reguard();
    }
}
