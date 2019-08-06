<?php

use Illuminate\Database\Seeder;
use App\Aptitude;

class AptitudesTableSeeder extends Seeder
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
                'volunteer_type_id' => 1,
                'name' => 'Basic Evidence',
            ],
            [
                'id' => 2,
                'volunteer_type_id' => 1,
                'name' => 'Genetic Evidence',
            ],
            [
                'id' => 3,
                'volunteer_type_id' => 2,
                'name' => 'Gene - Basic'
            ],
            [
                'id' => 4,
                'volunteer_type_id' => 2,
                'name' => 'Variant - Basic'
            ],
            [
                'id' => 5,
                'volunteer_type_id' => 2,
                'name' => 'Variant - Proficiency'
            ],
            [
                'id' => 6,
                'volunteer_type_id' => 2,
                'name' => 'Dosage - Basic'
            ],
            [
                'id' => 7,
                'volunteer_type_id' => 2,
                'name' => 'Actionability - Basic'
            ],
            [
                'id' => 8,
                'volunteer_type_id' => 2,
                'name' => 'Somatic Variant - Basic'
            ],
        ];

        foreach ($items as $item) {
            Aptitude::updateOrCreate(
                ['id' => $item['id']],
                $item
            );
        }
    }
}
