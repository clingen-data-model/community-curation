<?php

use App\UploadCategory;
use Illuminate\Database\Seeder;

class UploadCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (['Resume/CV', 'Transcript', 'Letter of Reference', 'Cover Letter', 'Attestation'] as $name) {
            UploadCategory::firstOrCreate(compact('name'));
        }
    }
}
