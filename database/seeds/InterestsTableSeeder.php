<?php

use Illuminate\Database\Seeder;
use App\Interest;

class InterestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            1 => 'Cardiovascular',
            2 => 'Hearing Loss',
            3 => 'Hemostasis/Thrombosis',
            4 => 'Hereditary Cancer',
            5 => 'Inborn Errors of Metabolism',
            6 => 'Neurodevelopmental',
            7 => 'RASopathy',
            8 => 'Neuromuscular',
            9 => 'Ophthalmology',
        ];

        Interest::unguard();
        foreach ($items as $id => $name) {
            Interest::updateOrCreate(compact('id'), compact('id', 'name'));
        }
        Interest::reguard();
    }
}
