<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionTablesSeeder::class);
        $this->call(CampaignsTableSeeder::class);
        $this->call(GoalsTableSeeder::class);
        $this->call(InterestsTableSeeder::class);
        $this->call(MotivationsTableSeeder::class);
        $this->call(SelfDescriptionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
