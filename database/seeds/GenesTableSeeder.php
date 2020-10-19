<?php

namespace Database\Seeders;

use App\Gene;
use Illuminate\Database\Seeder;

class GenesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genes = [
            [
                'symbol' => 'LMNA',
                'hgnc_id' => 'HGNC:6636',
            ],
            [
                'symbol' => 'EXTL3',
                'hgnc_id' => 'HGNC:3518',
            ],
            [
                'symbol' => 'PCDH15',
                'hgnc_id' => 'HGNC:14674',
            ],
            [
                'symbol' => 'ABCC9',
                'hgnc_id' => 'HGNC:60',
            ],
        ];

        foreach ($genes as $gene) {
            Gene::updateOrCreate($gene);
        }
    }
}
