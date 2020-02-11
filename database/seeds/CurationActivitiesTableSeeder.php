<?php

use Illuminate\Database\Seeder;
use App\CurationActivity;
use App\CurationActivityType;

class CurationActivitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            1 => 'Actionability',
            2 => 'Dosage',
            3 => 'Gene',
            4 => 'Somatic Variant',
            5 => 'Variant',
        ];

        CurationActivity::unguard();
        foreach ($items as $id => $name) {
            $curation_activity_type_id = 1;
            CurationActivity::updateOrCreate(compact('id'), compact('name', 'curation_activity_type_id'));
        }

        CurationActivity::updateOrCreate(['id' => 6], [
            'name' => 'Baseline',
            'curation_activity_type_id' => 2
        ]);

        CurationActivity::reguard();
    }
}
