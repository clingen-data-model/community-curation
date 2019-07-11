<?php

use Illuminate\Database\Seeder;
use App\CurationActivity;

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
            CurationActivity::firstOrCreate(compact('id'), compact('id', 'name'));
        }
        CurationActivity::reguard();
    }
}
