<?php

use App\AssignmentStatus;
use Illuminate\Database\Seeder;

class AssignmentStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AssignmentStatus::unguard();
        foreach ([1 => 'active', 2=>'retired'] as $id => $name) {
            AssignmentStatus::firstOrCreate(compact('id', 'name'));
        }
        AssignmentStatus::reguard();
    }
}
