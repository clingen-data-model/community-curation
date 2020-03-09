<?php

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
        seedFromConfig('project.upload-categories', Classification::class);
    }
}
