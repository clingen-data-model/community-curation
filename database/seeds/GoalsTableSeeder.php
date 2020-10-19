<?php

namespace Database\Seeders;

use App\Goal;
use Illuminate\Database\Seeder;

class GoalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            1 => 'Learn about genetics',
            2 => 'Learn about biocuration',
            3 => 'Learn best practices for variant pathogenicity interpretation',
            4 => 'Learn best practices for gene-disease validity',
            5 => 'Learn best practices for actionability reporting',
            6 => 'Learn best practices for dosage sensitivity',
            7 => 'Networking and collaboration',
            8 => 'Contribute and promote advancements in clinical genomics and precision medicine',
            9 => 'Support and contribute domain expertise to the goals of ClinGen',
            10 => 'Obtain knowledge and skill for future career plans',
            11 => 'Increase knowledge in a specific disease domain ',
            100 => 'Other',
        ];

        Goal::unguard();
        foreach ($items as $id => $name) {
            Goal::updateOrCreate(compact('id'), compact('id', 'name'));
        }
        Goal::reguard();
    }
}
