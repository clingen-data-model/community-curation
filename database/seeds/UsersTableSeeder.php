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
        
        // $admin = User::firstOrcreate(
        //     [
        //         'email' => 'courtney_thaxton@med.unc.edu',
        //     ],
        //     [
        //         'first_name' => 'Courtney',
        //         'last_name' => 'Thaxton',
        //         'email' => 'courtney_thaxton@med.unc.edu',
        //         'password' => Hash::make(uniqid()),
        //         'country_id' => 225
        //     ]
        // );

        $admins = [
            ['first_name' => 'Courtney', 'last_name' => 'Thaxton', 'email' => 'courtney_thaxton@med.unc.edu'],
            [ 'first_name' => "Elizabeth", 'last_name' => 	"Kearns", 'email' =>	"liz_kearns@med.unc.edu"],
            [ 'first_name' => "Erin", 'last_name' =>	"Riggs", 'email' =>	"eriggs@geisinger.edu"],
            [ 'first_name' => "Marina", 'last_name' =>	"DiStefano", 'email' =>	"mdistefano1@bwh.harvard.edu"],
            [ 'first_name' => "Laura", 'last_name' =>	"Milko", 'email' =>	"laura_milko@med.unc.edu"],
            [ 'first_name' => "Danielle", 'last_name' =>	"Azzaitti", 'email' =>	"dazzarit@broadinstitute.org"],
            [ 'first_name' => "Jennifer", 'last_name' =>	"Goldstein", 'email' =>	"goldjen@email.unc.edu"],
            [ 'first_name' => "Shruti", 'last_name' =>	"Rao", 'email' =>	"sr879@georgetown.edu"],
            [ 'first_name' => "Deb", 'last_name' =>	"Ritter", 'email' =>	"dritter@bcm.edu"],
            [ 'first_name' => "Taylor", 'last_name' =>	"Bingaman", 'email' =>	"tibingaman@geisinger.edu"],
            [ 'first_name' => "Chris", 'last_name' =>	"Catlin", 'email' =>	"chris.l.catlin@kpchr.org"],
            [ 'first_name' => "Christine", 'last_name' =>	"Pak", 'email' =>	"christine.pak@kpchr.org"],
            [ 'first_name' => "Lianna", 'last_name' =>	"Paul", 'email' =>	"ldpaul@geisinger.edu"],
            [ 'first_name' => "Molly", 'last_name' =>	"Good", 'email' =>	"megood1@geisinger.edu"],
        ];
        
        foreach ($admins as $admin) {
            $user = User::firstOrCreate(
                [
                    'email' => $admin['email']
                ],
                array_merge(
                    $admin,
                    [
                        'password' => (app()->environment('production')) ? Hash::make(uniqid()) : Hash::make('tester'),
                        'country_id' => 225
                    ]
                )
            );
            $user->assignRole('admin');
        }
    }
}
