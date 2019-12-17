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
        foreach (config('project.assignment-statuses') as $name => $id) {
            AssignmentStatus::firstOrCreate(compact('id'), compact('id', 'name'));
        }
        AssignmentStatus::reguard();
    }
}
