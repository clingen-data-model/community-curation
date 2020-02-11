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
            [
                'id' => 1,
                'name' => 'Actionability',
                'legacy_name' => 'Actionability'
            ],
            [
                'id' => 2,
                'name' => 'Dosage',
                'legacy_name' => 'Dosage Sensitivity'
            ],
            [
                'id' => 3,
                'name' => 'Gene',
                'legacy_name' => 'Gene Disease Validity'
            ],
            [
                'id' => 4,
                'name' => 'Somatic Variant',
                'legacy_name' => 'Somatic Cancer'
            ],
            [
                'id' => 5,
                'name' => 'Variant',
                'legacy_name' => 'Variant Pathogenicity'
            ],
        ];

        CurationActivity::unguard();
        foreach ($items as $data) {
            $data['curation_activity_type_id'] = 1;
            CurationActivity::updateOrCreate(['id' => $data['id']], $data);
        }

        CurationActivity::updateOrCreate(['id' => 6], [
            'name' => 'Baseline',
            'curation_activity_type_id' => 2
        ]);

        CurationActivity::reguard();
    }
}
