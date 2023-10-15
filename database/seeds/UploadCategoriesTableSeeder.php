<?php

namespace Database\Seeders;

use App\UploadCategory;
use Illuminate\Database\Seeder;

class UploadCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        seedFromConfig('project.upload-categories', UploadCategory::class);
    }
}
