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
        $this->call(CountriesTableSeeder::class);
        $this->call(AssignmentStatusesTableSeeder::class);
        $this->call(RolesAndPermissionTablesSeeder::class);
        $this->call(CampaignsTableSeeder::class);
        $this->call(CurationActivityTypesTableSeeder::class);
        $this->call(CurationActivitiesTableSeeder::class);
        $this->call(VolunteerTypesTableSeeder::class);
        $this->call(VolunteerStatusesTableSeeder::class);
        $this->call(AptitudesTableSeeder::class);
        $this->call(WorkingGroupsTableSeeder::class);
        $this->call(ExpertPanelsTableSeeder::class);
        $this->call(GoalsTableSeeder::class);
        $this->call(InterestsTableSeeder::class);
        $this->call(MotivationsTableSeeder::class);
        $this->call(SelfDescriptionsTableSeeder::class);
        $this->call(AptitudesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
