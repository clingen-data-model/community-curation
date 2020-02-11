<?php

use App\CurationActivityType;
use Illuminate\Database\Seeder;

class CurationActivityTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('project.curation-activity-types') as $name => $id) {
            CurationActivityType::updateOrCreate(compact('id'), compact('name'));
        }
    }
}
