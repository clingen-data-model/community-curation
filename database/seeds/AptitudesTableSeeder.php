<?php

namespace Database\Seeders;

use App\Aptitude;
use App\Aptitudes\Evaluators\BasicAptitudeEvaluator;
use App\Scopes\ActiveAptitudeScope;
use Illuminate\Database\Seeder;

class AptitudesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $aptitudes = [
            [
                'id' => 1,
                'name' => 'Actionability, Basic',
                'subject_type' => \App\CurationActivity::class,
                'subject_id' => 1,
                'training_materials_url' => 'https://www.clinicalgenome.org/curation-activities/clinical-actionability/training-materials/',
                'volunteer_type_id' => 2,
                'is_primary' => 1,
                'evaluator_class' => BasicAptitudeEvaluator::class,
            ],
            [
                'id' => 2,
                'name' => 'Dosage, Basic',
                'subject_type' => \App\CurationActivity::class,
                'subject_id' => 2,
                'training_materials_url' => 'https://clinicalgenome.org/curation-activities/dosage-sensitivity/training-materials/',
                'volunteer_type_id' => 2,
                'is_primary' => 1,
                'evaluator_class' => BasicAptitudeEvaluator::class,
            ],
            [
                'id' => 3,
                'name' => 'Gene, Basic',
                'subject_type' => \App\CurationActivity::class,
                'subject_id' => 3,
                'training_materials_url' => 'https://www.clinicalgenome.org/curation-activities/gene-disease-validity/training-materials/',
                'volunteer_type_id' => 2,
                'is_primary' => 1,
                'evaluator_class' => BasicAptitudeEvaluator::class,
            ],
            [
                'id' => 4,
                'name' => 'Somatic Variant, Basic',
                'subject_type' => \App\CurationActivity::class,
                'subject_id' => 4,
                'training_materials_url' => 'https://www.clinicalgenome.org/curation-activities/somatic/training-materials/',
                'volunteer_type_id' => 2,
                'is_primary' => 1,
                'evaluator_class' => BasicAptitudeEvaluator::class,
            ],
            [
                'id' => 5,
                'name' => 'Variant, Basic',
                'subject_type' => \App\CurationActivity::class,
                'subject_id' => 5,
                'training_materials_url' => 'https://www.clinicalgenome.org/curation-activities/variant-pathogenicity/training-materials/',
                'volunteer_type_id' => 2,
                'is_primary' => 1,
                'evaluator_class' => BasicAptitudeEvaluator::class,
            ],
            [
                'id' => 6,
                'name' => 'Variant, Proficiency',
                'subject_type' => \App\CurationActivity::class,
                'subject_id' => 5,
                'training_materials_url' => null,
                'volunteer_type_id' => 2,
                'is_primary' => 0,
                'evaluator_class' => BasicAptitudeEvaluator::class,
                'is_active' => 0,
            ],
            [
                'id' => 7,
                'name' => 'Baseline, Basic Evidence',
                'training_materials_url' => null,
                'volunteer_type_id' => 1,
                'subject_type' => \App\CurationActivity::class,
                'subject_id' => 6,
                'is_primary' => 0,
                'is_active' => 0,
                'evaluator_class' => BasicAptitudeEvaluator::class,
            ],
            [
                'id' => 8,
                'name' => 'Baseline, Genetic Evidence',
                'training_materials_url' => 'https://clinicalgenome.org/curation-activities/baseline-annotation/baseline-annotation-training-materials/',
                'volunteer_type_id' => 1,
                'subject_type' => \App\CurationActivity::class,
                'subject_id' => 6,
                'is_primary' => 1,
                'evaluator_class' => BasicAptitudeEvaluator::class,
            ],
        ];

        foreach ($aptitudes as $aptitude) {
            Aptitude::withoutGlobalScope(ActiveAptitudeScope::class)->updateOrCreate(['id' => $aptitude['id']], $aptitude);
        }
    }
}
