<?php

use Illuminate\Database\Seeder;
use App\Campaign;

class CampaignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaigns = [
            1 => 'CCG 2019',
            2 => 'ACMG 2019',
            3 => 'C3 Sticker',
            4 => 'ClinGen Community Curation business card',
            5 => 'ClinGen advertisement booth',
            6 => 'ClinGen workshop',
            7 => 'ClinGen poster or presentation',
            8 => 'Networking',
            9 => 'Colleagues',
            10 => 'ClinGen newsletter',
            11 => 'ClinGen affiliated emails',
            12 => 'The clinicalgenome.org website',
            13 => 'The Community Curation Working Group webpage',
            14 => 'Suggestion from mentor',
            15 => 'Educational coursework',
            16 => 'Twitter',
            17 => 'Facebook',
            18 => 'Youtube',
            100 => 'Other',
        ];

        Campaign::unguard();
        foreach ($campaigns as $id => $name) {
            Campaign::firstOrCreate(['id' => $id], [
                'name' => $name
            ]);
        }
        Campaign::reguard();
    }
}
