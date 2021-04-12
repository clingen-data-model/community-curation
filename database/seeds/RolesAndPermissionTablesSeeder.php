<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->createPermissionGroup('users', ['list', 'create', 'update', 'delete']);
        $this->createPermissionGroup('curation-activities', ['list', 'create', 'update', 'delete']);
        $this->createPermissionGroup('curation-groups', ['list', 'create', 'update', 'delete']);
        $this->createPermissionGroup('working-groups', ['list', 'create', 'update', 'delete']);
        $this->createPermissionGroup('volunteer-types', ['list', 'create', 'update', 'delete']);
        $this->createPermissionGroup('volunteer-statuses', ['list', 'create', 'update', 'delete']);
        $this->createPermissionGroup('lookups', ['list', 'create', 'update', 'delete']);
        $this->createPermissionGroup('attestations', ['list', 'create', 'update', 'delete']);
        $this->createPermissionGroup('uploads', ['list', 'create', 'create for others', 'update', 'delete']);
        $this->createPermissionGroup('genes', ['list', 'create', 'update', 'delete']);
        $this->createPermissionGroup('trainings', ['list', 'create', 'update', 'delete']);
        $this->createPermissionGroup('faq', ['list', 'create', 'update', 'delete']);
        $this->createPermissionGroup('notes', ['list', 'create', 'update', 'delete']);

        $administerPermission = Permission::firstOrCreate(['name' => 'administer']);
        $canImpersonatePermission = Permission::firstOrCreate(['name' => 'impersonate']);
        $canViewLogsPermission = Permission::firstOrCreate(['name' => 'view logs']);
        $runReports = Permission::firstOrCreate(['name' => 'run reports']);
        $seeAlreadyMember = Permission::firstOrCreate(['name' => 'see clingen member info']);
        $setAlreadyMember = Permission::firstOrCreate(['name' => 'set clingen member info']);

        $programmer = Role::firstOrCreate(['name' => 'programmer']);
        $this->giveActionPermissionsToRole($programmer, 'users', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'curation-activities', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'curation-groups', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'working-groups', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'volunteer-types', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'volunteer-statuses', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'lookups', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'attestations', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'uploads', ['list', 'create', 'create for others', 'update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'genes', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'trainings', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'faq', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'notes', ['list', 'create', 'update', 'delete']);
        $this->givePermissionToRole($programmer, $administerPermission);
        $this->givePermissionToRole($programmer, $canImpersonatePermission);
        $this->givePermissionToRole($programmer, $canViewLogsPermission);
        $this->givePermissionToRole($programmer, $runReports);
        $this->givePermissionToRole($programmer, $seeAlreadyMember);
        $this->givePermissionToRole($programmer, $setAlreadyMember);

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $this->giveActionPermissionsToRole($superAdmin, 'users', ['list', 'create', 'update']);
        $this->giveActionPermissionsToRole($superAdmin, 'curation-activities', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($superAdmin, 'curation-groups', ['list', 'create', 'update']);
        $this->giveActionPermissionsToRole($superAdmin, 'working-groups', ['list', 'create', 'update']);
        $this->giveActionPermissionsToRole($superAdmin, 'volunteer-statuses', ['list', 'update']);
        $this->giveActionPermissionsToRole($superAdmin, 'lookups', ['list', 'create', 'update']);
        $this->giveActionPermissionsToRole($superAdmin, 'attestations', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($superAdmin, 'uploads', ['list', 'create', 'create for others', 'update', 'delete']);
        $this->giveActionPermissionsToRole($superAdmin, 'genes', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($superAdmin, 'trainings', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($superAdmin, 'faq', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($superAdmin, 'notes', ['list', 'create', 'update', 'delete']);
        $this->givePermissionToRole($superAdmin, $administerPermission);
        $this->givePermissionToRole($superAdmin, $canImpersonatePermission);
        $this->givePermissionToRole($superAdmin, $runReports);
        $this->givePermissionToRole($superAdmin, $seeAlreadyMember);
        $this->givePermissionToRole($superAdmin, $setAlreadyMember);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $this->giveActionPermissionsToRole($admin, 'users', ['list', 'create', 'update']);
        $this->giveActionPermissionsToRole($admin, 'curation-activities', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($admin, 'curation-groups', ['list', 'create', 'update']);
        $this->giveActionPermissionsToRole($admin, 'working-groups', ['list', 'create', 'update']);
        $this->giveActionPermissionsToRole($admin, 'volunteer-statuses', ['list', 'update']);
        $this->giveActionPermissionsToRole($admin, 'lookups', ['list', 'create', 'update']);
        $this->giveActionPermissionsToRole($admin, 'attestations', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($admin, 'uploads', ['list', 'create', 'create for others', 'update', 'delete']);
        $this->giveActionPermissionsToRole($admin, 'genes', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($admin, 'trainings', ['list', 'create', 'update', 'delete']);
        $this->giveActionPermissionsToRole($admin, 'notes', ['list', 'create', 'update', 'delete']);
        $this->givePermissionToRole($admin, $administerPermission);
        $this->givePermissionToRole($admin, $canImpersonatePermission);
        $this->givePermissionToRole($admin, $runReports);
        $this->givePermissionToRole($programmer, $seeAlreadyMember);
        $this->givePermissionToRole($programmer, $setAlreadyMember);

        $coordinator = Role::firstOrCreate(['name' => 'coordinator']);
        $volunteer = Role::firstOrCreate(['name' => 'volunteer']);
    }

    protected function giveActionPermissionsToRole($role, $entity, $actions = null)
    {
        $actions = $actions ?? ['list', 'create', 'update', 'delete'];
        foreach ($actions as $action) {
            $perm = $action.' '.$entity;
            if (!$role->hasPermissionTo($perm)) {
                $role->givePermissionTo($perm);
            }
        }
    }

    protected function createPermissionGroup($entity, $actions = null)
    {
        $actions = $actions ?? ['list', 'create', 'update', 'delete'];
        foreach ($actions as $action) {
            $perm = $action.' '.$entity;
            Permission::firstOrcreate(['name' => $perm]);
        }
    }

    protected function givePermissionToRole($role, $perm)
    {
        if (!$role->hasPermissionTo($perm)) {
            $role->givePermissionTo($perm);
        }
    }
}
