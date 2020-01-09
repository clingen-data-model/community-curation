<?php

use App\Aptitude;
use App\Training;
use Illuminate\Database\Seeder;

class AptitudesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aptitudes = [
            [
                'id' => 1,
                'name' => 'Actionability, Basic',
                'subject_type' => 'App\CurationActivity',
                'subject_id' => 1,
                'training_materials_url' => 'https://www.clinicalgenome.org/curation-activities/clinical-actionability/training-materials/',
                'volunteer_type_id' => 2
            ],
            [
                'id' => 2,
                'name' => 'Dosage, Basic',
                'subject_type' => 'App\CurationActivity',
                'subject_id' => 2,
                'training_materials_url' => 'https://clinicalgenome.org/curation-activities/dosage-sensitivity/training-materials/',
                'volunteer_type_id' => 2
            ],
            [
                'id' => 3,
                'name' => 'Gene, Basic',
                'subject_type' => 'App\CurationActivity',
                'subject_id' => 3,
                'training_materials_url' => 'https://www.clinicalgenome.org/curation-activities/gene-disease-validity/training-materials/
',
                'volunteer_type_id' => 2
            ],
            [
                'id' => 4,
                'name' => 'Somantic Variant, Basic',
                'subject_type' => 'App\CurationActivity',
                'subject_id' => 4,
                'training_materials_url' => 'https://www.clinicalgenome.org/curation-activities/somatic/training-materials/',
                'volunteer_type_id' => 2
            ],
            [
                'id' => 5,
                'name' => 'Variant, Basic',
                'subject_type' => 'App\CurationActivity',
                'subject_id' => 5,
                'training_materials_url' => 'https://www.clinicalgenome.org/curation-activities/variant-pathogenicity/training-materials/',
                'volunteer_type_id' => 2
            ],
            [
                'id' => 6,
                'name' => 'Variant, Proficiency',
                'subject_type' => 'App\CurationActivity',
                'subject_id' => 5,
                'training_materials_url' => 'https://www.google.com',
                'volunteer_type_id' => 2
            ],
            [
                'id' => 7,
                'name' => 'Basic Evidence',
                'training_materials_url' => 'https://www.google.com',
                'volunteer_type_id' => 1
            ],
            [
                'id' => 8,
                'name' => 'Genetic Evidence',
                'training_materials_url' => 'https://www.google.com',
                'volunteer_type_id' => 1
            ]
        ];

        foreach ($aptitudes as $aptitude) {
            Aptitude::firstOrCreate(['id' => $aptitude['id']], $aptitude);
        }
    }
}
