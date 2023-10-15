<?php

namespace Database\Seeders;

use App\CurationActivityType;
use Illuminate\Database\Seeder;

class CurationActivityTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (config('project.curation-activity-types') as $name => $id) {
            CurationActivityType::updateOrCreate(compact('id'), compact('name'));
        }
    }
}
