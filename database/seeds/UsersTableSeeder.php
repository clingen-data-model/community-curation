<?php

namespace Database\Seeders;

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
    public function run(): void
    {
        User::unguard();
        $programmer = User::firstOrCreate(['id' => 1], [
            'id' => 1,
            'first_name' => 'Super',
            'last_name' => 'User',
            'email' => 'super.user@example.com',
            'password' => Hash::make('tester'),
            'country_id' => 225,
            'timezone' => 'America/New_York',
        ]);
        $programmer->assignRole('programmer');

        $superAdmins = [
            [],
        ];

        $superAdmin = User::firstOrCreate([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'super.admin@example.com',
            'password' => Hash::make('tester'),
        ]);
        $superAdmin->assignRole('super-admin');

        $superAdmin = User::firstOrCreate([
            'first_name' => 'Normal',
            'last_name' => 'Admin',
            'email' => 'normal.admin@example.com',
            'password' => Hash::make('tester'),
        ]);
        $superAdmin->assignRole('admin');
    }
}
