<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::unguard();
        $programmer = User::firstOrCreate(['id'=>1], [
            'id' => 1,
            'first_name' => 'Sirs',
            'last_name' => 'Programmer',
            'email' => 'sirs@unc.edu',
            'password' => \Hash::make('tester'),
            'country_id' => 225
        ]);
        $programmer->assignRole('programmer');
        
        $admin = User::firstOrcreate(
            [
                'email' => 'admin@test.com',
            ],
            [
                'first_name' => 'Tester',
                'last_name' => 'Admin',
                'email' => 'admin@test.com',
                'password' => \Hash::make('tester'),
                'country_id' => 225
            ]
        );
        $admin->assignRole('admin');
        
        $admin = User::firstOrcreate(
            [
                'email' => 'volunteer@clinicalgenome.org',
            ],
            [
                'first_name' => 'Coordinator',
                'last_name' => 'Tester',
                'email' => 'volunteer@clinicalgenome.org',
                'password' => \Hash::make('tester'),
                'country_id' => 225
            ]
        );
        $admin->assignRole('coordinator');
                
        $basicVolunteer = User::firstOrcreate(
            [
                'email' => 'basic@test.com',
            ],
            [
                'first_name' => 'Basic',
                'last_name' => 'Volunteer',
                'email' => 'basic@test.com',
                'password' => \Hash::make('tester'),
                'volunteer_status_id' => 1,
                'volunteer_type_id' => 1,
                'country_id' => 225
            ]
        );
        $basicVolunteer->assignRole('volunteer');
        
        $comprehensiveVolunteer = User::firstOrcreate(
            [
                'email' => 'comprehensive@test.com',
            ],
            [
                'first_name' => 'Comprehensive',
                'last_name' => 'Volunteer',
                'email' => 'comprehensive@test.com',
                'password' => \Hash::make('tester'),
                'volunteer_status_id' => 1,
                'volunteer_type_id' => 2,
                'country_id' => 225
            ]
        );
        $comprehensiveVolunteer->assignRole('volunteer');
    }
}
