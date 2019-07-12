<?php

use App\ExpertPanel;
use Illuminate\Database\Seeder;

class ExpertPanelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ExpertPanel::class, 17)->create();
    }
}
