<?php

namespace Database\Seeders;

use App\WorkingGroup;
use Illuminate\Database\Seeder;

class WorkingGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $workingGroups = [
            ['id' => 1, 'name' => 'Neurodevelopmental Disorders CDWG'],
            ['id' => 2, 'name' => 'Hearing Loss CDWG'],
            ['id' => 3, 'name' => 'Cardiovascular CDWG'],
            ['id' => 4, 'name' => 'Inborn Errors Metabolism CDWG'],
            ['id' => 5, 'name' => 'Hereditary Cancer CDWG'],
            ['id' => 6, 'name' => 'Hemostasis/Thrombosis CDWG'],
            ['id' => 7, 'name' => 'RASopathy CDWG'],
            ['id' => 8, 'name' => 'Gene Curation Working Group'],
            ['id' => 9, 'name' => 'Neuromuscular CDWG'],
            ['id' => 10, 'name' => 'Actionability'],
            ['id' => 11, 'name' => 'Dosage'],
            ['id' => 12, 'name' => 'Somatic'],
            ['id' => 13, 'name' => 'Epilepsy'],
            ['id' => 14, 'name' => 'Dilated Cardiomyopathy'],
            ['id' => 15, 'name' => 'Aminoacidopathy'],
            ['id' => 16, 'name' => 'Lysosomal Storage Disorders'],
            ['id' => 17, 'name' => 'Phenylketonuria'],
            ['id' => 18, 'name' => 'CDH1'],
            ['id' => 19, 'name' => 'Malignant Hyperthermia Susceptibility'],
            ['id' => 20, 'name' => 'Ocular'],
            ['id' => 21, 'name' => 'Kidney'],
        ];

        WorkingGroup::unguard();
        foreach ($workingGroups as $group) {
            WorkingGroup::updateOrCreate(
                [
                    'id' => $group['id'],
                ],
                $group
            );
        }
        WorkingGroup::reguard();
    }
}
