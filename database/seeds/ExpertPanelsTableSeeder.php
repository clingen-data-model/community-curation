<?php

use App\ExpertPanel;
use Illuminate\Database\Seeder;

class ExpertPanelsTableSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expertPanels = [
            [
                'name' => 'Intellectual Disability and Autism GCEP',
                'working_group_id' => 1,
                'curation_activity_id' => 3
            ],
            [
                'name' => 'Brain Malformations GCEP',
                'working_group_id' => 1,
                'curation_activity_id' => 3
            ],
            [
                'name' => 'Hereditary Cancer GCEP',
                'working_group_id' => 5,
                'curation_activity_id' => 3
            ],
            [
                'name' => 'Hemostasis/ Thrombosis GCEP',
                'working_group_id' => 6,
                'curation_activity_id' => 3
            ],
            [
                'name' => 'Mitochondrial Diseases GCEP',
                'working_group_id' => 4,
                'curation_activity_id' => 3
            ],
            [
                'name' => 'Monogenic Diabetes GCEP',
                'working_group_id' => 4,
                'curation_activity_id' => 3
            ],
            [
                'name' => 'Congenital Myopathies GCEP',
                'working_group_id' => 9,
                'curation_activity_id' => 3
            ],
            [
                'name' => 'Charcot Marie Tooth GCEP',
                'working_group_id' => 9,
                'curation_activity_id' => 3
            ],
            [
                'name' => 'Limb Girdle Muscular Dystrophy GCEP',
                'working_group_id' => 9,
                'curation_activity_id' => 3
            ],
            [
                'name' => 'RASopathy VCEP',
                'working_group_id' => 7,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'FBN1 VCEP',
                'working_group_id' => 3,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Cardiomyopathy VCEP',
                'working_group_id' => 3,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Familial Hypercholesterolemia VCEP',
                'working_group_id' => 3,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'PTEN VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Myeloid Malignancy VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'VHL VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'TP53 VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'CDH1 VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'DICER1 VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Colorectal Cancer VCEP',
                'working_group_id' => 5,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'ACADVL VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Lysosomal Disorders ',
                'working_group_id' => 4,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Monogenic Diabetes VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Cerebral Creatine Deficiency Syndrome VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Phenylketonuria VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Peroxisomal Disorders VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'OTC VCEP',
                'working_group_id' => 4,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Hearing Loss VCEP',
                'working_group_id' => 2,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Coagulation Factor Deficiencies VCEP',
                'working_group_id' => 6,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Platelet Disorders VCEP',
                'working_group_id' => 6,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Brain Malformations VCEP',
                'working_group_id' => 1,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Limb Girdle Muscular dystrophy VCEP',
                'working_group_id' => 9,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Congenital Myopathies VCEP',
                'working_group_id' => 9,
                'curation_activity_id' => 5
            ],
            [
                'name' => 'Dosage-Recurrent Regions',
                'working_group_id' => 11,
                'curation_activity_id' => 2
            ],
            [
                'name' => 'Dosage-Neurodevelopmental',
                'working_group_id' => 11,
                'curation_activity_id' => 2
            ],
            [
                'name' => 'Dosage-Hereditary Cancer',
                'working_group_id' => 11,
                'curation_activity_id' => 2
            ],
            [
                'name' => 'Somatic-Pediatric',
                'working_group_id' => 12,
                'curation_activity_id' => 4
            ],
            [
                'name' => 'Somatic-Pancreatic',
                'working_group_id' => 12,
                'curation_activity_id' => 4
            ],
            [
                'name' => 'Somatic-Genitourinary Tract Cancer',
                'working_group_id' => 12,
                'curation_activity_id' => 4
            ],
            [
                'name' => 'Actionability-Pediatric',
                'working_group_id' => 10,
                'curation_activity_id' => 1
            ]
        ];

        foreach ($expertPanels as $panel) {
            ExpertPanel::firstOrCreate($panel);
        }
    }
}
