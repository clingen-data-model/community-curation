<?php

use Illuminate\Database\Seeder;
use App\WorkingGroup;

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
            ['id'=>1, 'name'=>'Neurodevelopmental Disorders CDWG'],
            ['id'=>2, 'name'=>'Hearing Loss CDWG'],
            ['id'=>3, 'name'=>'Cardiovascular CDWG'],
            ['id'=>4, 'name'=>'Inborn Errors Metabolism CDWG'],
            ['id'=>5, 'name'=>'Hereditary Cancer CDWG'],
            ['id'=>6, 'name'=>'Hemostasis/Thrombosis CDWG'],
            ['id'=>7, 'name'=>'RASopathy CDWG'],
            ['id'=>8, 'name'=>'Gene Curation Working Group'],
            ['id'=>9, 'name'=>'Neuromuscular CDWG'],
            ['id'=>10, 'name'=>'Actionability'],
        ];

        WorkingGroup::unguard();
        foreach ($workingGroups as $group) {
            WorkingGroup::updateOrCreate(
                [
                    'id'=>$group['id']
                ], 
                $group
            );
        }
        WorkingGroup::reguard();
    }
}