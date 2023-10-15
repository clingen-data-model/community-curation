<?php

namespace Database\Seeders;

use App\CurationGroup;
use Illuminate\Database\Seeder;

class CurationGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $curationGroups = [
            [
                'name' => 'Intellectual Disability and Autism GCEP',
                'working_group_id' => 1,
                'curation_activity_id' => config('project.curation-activities.gene'),
            ],
            [
                'name' => 'Brain Malformations GCEP',
                'working_group_id' => 1,
                'curation_activity_id' => config('project.curation-activities.gene'),
            ],
            [
                'name' => 'Hereditary Cancer GCEP',
                'working_group_id' => 5,
                'curation_activity_id' => config('project.curation-activities.gene'),
            ],
            [
                'name' => 'Hemostasis/ Thrombosis GCEP',
                'working_group_id' => 6,
                'curation_activity_id' => config('project.curation-activities.gene'),
            ],
            [
                'name' => 'Mitochondrial Diseases GCEP',
                'working_group_id' => 4,
                'curation_activity_id' => config('project.curation-activities.gene'),
            ],
            [
                'name' => 'Mitochondrial Diseases VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Monogenic Diabetes GCEP',
                'working_group_id' => 4,
                'curation_activity_id' => config('project.curation-activities.gene'),
            ],
            [
                'name' => 'Congenital Myopathies GCEP',
                'working_group_id' => 9,
                'curation_activity_id' => config('project.curation-activities.gene'),
            ],
            [
                'name' => 'Charcot Marie Tooth GCEP',
                'working_group_id' => 9,
                'curation_activity_id' => config('project.curation-activities.gene'),
            ],
            [
                'name' => 'Limb Girdle Muscular Dystrophy GCEP',
                'working_group_id' => 9,
                'curation_activity_id' => config('project.curation-activities.gene'),
            ],
            [
                'name' => 'Limb Girdle Muscular Dystrophy VCEP',
                'working_group_id' => 9,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'RASopathy VCEP',
                'working_group_id' => 7,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'FBN1 VCEP',
                'working_group_id' => 3,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Cardiomyopathy VCEP',
                'working_group_id' => 3,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Familial Hypercholesterolemia VCEP',
                'working_group_id' => 3,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'PTEN VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Myeloid Malignancy VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'VHL VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'TP53 VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'CDH1 VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'DICER1 VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Colorectal Cancer VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'ACADVL VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Lysosomal Disorders ',
                'working_group_id' => 4,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Monogenic Diabetes VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Cerebral Creatine Deficiency Syndrome VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Phenylketonuria VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Peroxisomal Disorders VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'OTC VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Hearing Loss VCEP',
                'working_group_id' => 2,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Coagulation Factor Deficiencies VCEP',
                'working_group_id' => 6,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Platelet Disorders VCEP',
                'working_group_id' => 6,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Brain Malformations VCEP',
                'working_group_id' => 1,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Limb Girdle Muscular dystrophy VCEP',
                'working_group_id' => 9,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Congenital Myopathies VCEP',
                'working_group_id' => 9,
                'curation_activity_id' => config('project.curation-activities.variant'),
            ],
            [
                'name' => 'Dosage-Recurrent Regions',
                'working_group_id' => 11,
                'curation_activity_id' => config('project.curation-activities.dosage'),
            ],
            [
                'name' => 'Dosage-Neurodevelopmental',
                'working_group_id' => 11,
                'curation_activity_id' => config('project.curation-activities.dosage'),
            ],
            [
                'name' => 'Dosage-Hereditary Cancer',
                'working_group_id' => 11,
                'curation_activity_id' => config('project.curation-activities.dosage'),
            ],
            [
                'name' => 'Somatic-Pediatric',
                'working_group_id' => 12,
                'curation_activity_id' => config('project.curation-activities.somatic-variant'),
            ],
            [
                'name' => 'Somatic-Pancreatic',
                'working_group_id' => 12,
                'curation_activity_id' => config('project.curation-activities.somatic-variant'),
            ],
            [
                'name' => 'Somatic-Genitourinary Tract Cancer',
                'working_group_id' => 12,
                'curation_activity_id' => config('project.curation-activities.somatic-variant'),
            ],
            [
                'name' => 'Actionability-Adult/Pediatric',
                'working_group_id' => 10,
                'curation_activity_id' => config('project.curation-activities.actionability'),
            ],
            [
                'name' => 'Epilepsy GCEP',
                'working_group_id' => 13,
                'curation_activity_id' => config('project.curation-activities.gene'),
                'accepting_volunteers' => 0,
            ],
            [
                'name' => 'Dilated Cardiomyopathy GCEP',
                'working_group_id' => 14,
                'curation_activity_id' => config('project.curation-activities.gene'),
                'accepting_volunteers' => 0,
            ],
            [
                'name' => 'Aminoacidopathy GCEP',
                'working_group_id' => 15,
                'curation_activity_id' => config('project.curation-activities.gene'),
                'accepting_volunteers' => 0,
            ],
            [
                'name' => 'Storage Disease',
                'working_group_id' => 16,
                'curation_activity_id' => config('project.curation-activities.variant'),
                'accepting_volunteers' => 0,
            ],
            [
                'name' => 'PAH VCEP',
                'working_group_id' => 17,
                'curation_activity_id' => config('project.curation-activities.variant'),
                'accepting_volunteers' => 0,
            ],
            [
                'name' => 'CDH1 VCEP',
                'working_group_id' => 18,
                'curation_activity_id' => config('project.curation-activities.variant'),
                'accepting_volunteers' => 0,
            ],
            [
                'name' => 'RYR1 VCEP',
                'working_group_id' => 19,
                'curation_activity_id' => config('project.curation-activities.variant'),
                'accepting_volunteers' => 0,
            ],
            [
                'name' => 'Recurrent CNVs',
                'working_group_id' => 11,
                'curation_activity_id' => config('project.curation-activities.dosage'),
                'accepting_volunteers' => 0,
            ],
            [
                'name' => 'Retina GCEP',
                'working_group_id' => 20,
                'curation_activity_id' => config('project.curation-activities.gene'),
                'accepting_volunteers' => 1,
            ],
            [
                'name' => 'Glaucoma and Neuro-ocular GCEP',
                'working_group_id' => 20,
                'curation_activity_id' => config('project.curation-activities.gene'),
                'accepting_volunteers' => 1,
            ],
            [
                'name' => 'Cystic and Ciliopathy Disorders GCEP',
                'working_group_id' => 21,
                'curation_activity_id' => config('project.curation-activities.gene'),
                'accepting_volunteers' => 0,
            ],
            [
                'name' => 'Dosage',
                'working_group_id' => 11,
                'curation_activity_id' => config('project.curation-activities.dosage'),
                'accepting_volunteers' => 0,
            ],
        ];

        foreach ($curationGroups as $panel) {
            CurationGroup::firstOrCreate($panel);
        }
    }
}
