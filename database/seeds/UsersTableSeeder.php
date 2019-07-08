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
        $programmer = User::firstOrcreate(['email' => 'sirs@unc.edu'],[
            'name' => 'Sirs Programmer',
            'email' => 'sirs@unc.edu',
            'password' => \Hash::make('tester')
        ]);
        $programmer->assignRole('programmer');
        
        $admin = User::firstOrcreate(
            [
                'email' => 'admin@test.com',
            ],
            [
                'name' => 'Admin Tester',
                'email' => 'admin@test.com',
                'password' => \Hash::make('tester')
            ]
        );
        $admin->assignRole('admin');
        
        $admin = User::firstOrcreate(
            [
                'email' => 'coordinator@test.com',
            ],
            [
                'name' => 'Coordinator Tester',
                'email' => 'coordinator@test.com',
                'password' => \Hash::make('tester')
            ]
        );
        $admin->assignRole('coordinator');
                
        $admin = User::firstOrcreate(
            [
                'email' => 'volunteer@test.com',
            ],
            [
                'name' => 'Volunteer Tester',
                'email' => 'volunteer@test.com',
                'password' => \Hash::make('tester')
            ]
        );
        $admin->assignRole('volunteer');
        
    }
}
