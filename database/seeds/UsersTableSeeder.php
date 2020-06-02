<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'first_name' => 'TJ',
            'last_name' => 'Ward',
            'email' => 'jward3@email.unc.edu',
            'password' => Hash::make('tester'),
            'country_id' => 225
        ]);
        $programmer->assignRole('programmer');
        
        $admin = User::firstOrcreate(
            [
                'email' => 'courtney_thaxton@med.unc.edu',
            ],
            [
                'first_name' => 'Courtney',
                'last_name' => 'Thaxton',
                'email' => 'courtney_thaxton@med.unc.edu',
                'password' => Hash::make(uniqid()),
                'country_id' => 225
            ]
        );
        $admin->assignRole('admin');
    }
}
