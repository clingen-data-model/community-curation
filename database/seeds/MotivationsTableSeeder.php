<?php

namespace Database\Seeders;

use App\Motivation;
use Illuminate\Database\Seeder;

class MotivationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $motivations = [
            1 => 'I want to be involved with ClinGen.',
            2 => 'I want to contribute as a measure to promote precision medicine standards.',
            3 => 'I want to contribute to promote enhanced patient care.',
            4 => 'I want to network.',
            5 => 'I was encouraged by a mentor.',
            6 => 'I was encouraged by someone affiliated with ClinGen.',
            7 => 'I want to help out the community.',
            8 => 'I want to gain curation experience/improve my resume.',
            9 => 'I want to contribute due to personal experience with genetic disorders.',
            100 => 'Other',
        ];

        Motivation::unguard();
        foreach ($motivations as $id => $name) {
            Motivation::updateOrCreate(['id' => $id], [
                'id' => $id,
                'name' => $name,
                ]);
        }
        Motivation::reguard();
    }
}
