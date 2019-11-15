<?php

use App\Training;
use Illuminate\Database\Seeder;

class TrainingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trainings = [
            [
                'id' => 1,
                'name' => 'Actionability, Basic',
                'subject_type' => 'App\CurationActivity',
                'subject_id' => 1,
                'materials_url' => null,
            ],
            [
                'id' => 2,
                'name' => 'Dosage, Basic',
                'subject_type' => 'App\CurationActivity',
                'subject_id' => 2,
                'materials_url' => null,
            ],
            [
                'id' => 3,
                'name' => 'Gene, Basic',
                'subject_type' => 'App\CurationActivity',
                'subject_id' => 3,
                'materials_url' => null,
            ],
            [
                'id' => 4,
                'name' => 'Somantic Variant, Basic',
                'subject_type' => 'App\CurationActivity',
                'subject_id' => 4,
                'materials_url' => null,
            ],
            [
                'id' => 5,
                'name' => 'Variant, Basic',
                'subject_type' => 'App\CurationActivity',
                'subject_id' => 5,
                'materials_url' => null,
            ],
            [
                'id' => 6,
                'name' => 'Variant, Proficiency',
                'subject_type' => 'App\CurationActivity',
                'subject_id' => 5,
                'materials_url' => null,
            ],
        ];

        foreach ($trainings as $training) {
            Training::firstOrCreate(['id' => $training['id']], $training);
        }
    }
}
