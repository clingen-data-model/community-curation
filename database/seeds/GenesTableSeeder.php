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
            ],
            [
                'symbol' => 'EXTL3',
            ],
            [
                'symbol' => 'PCDH15',
            ],
            [
                'symbol' => 'ABCC9',
            ],
        ];

        foreach ($genes as $gene) {
            Gene::updateOrCreate($gene);
        }
    }
}
