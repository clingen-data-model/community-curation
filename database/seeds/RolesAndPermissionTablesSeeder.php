<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

        $this->createPermissionGroup('users', ['list','create','update','delete']);
        $this->createPermissionGroup('expert-panels', ['list','create','update','delete']);
        $this->createPermissionGroup('working-groups', ['list','create','update','delete']);
        $this->createPermissionGroup('volunteer-types', ['list','create','update','delete']);
        $this->createPermissionGroup('volunteer-statuses', ['list','create','update','delete']);
        $this->createPermissionGroup('lookups', ['list','create','update','delete']);
        $this->createPermissionGroup('attestations', ['list','create','update','delete']);
        $this->createPermissionGroup('uploads', ['list','create','create for others','update','delete']);

        $administerPermission = Permission::firstOrCreate(['name' => 'administer']);
        $canImpersonatePermission = Permission::firstOrCreate(['name' => 'impersonate']);
        $canViewLogsPermission = Permission::firstOrCreate(['name' => 'view logs']);

        $programmer = Role::firstOrCreate(['name' => 'programmer']);
        $this->giveActionPermissionsToRole($programmer, 'users', ['list', 'create','update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'expert-panels', ['list', 'create','update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'working-groups', ['list', 'create','update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'volunteer-types', ['list', 'create','update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'volunteer-statuses', ['list', 'create','update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'lookups', ['list', 'create','update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'attestations', ['list', 'create','update', 'delete']);
        $this->giveActionPermissionsToRole($programmer, 'uploads', ['list', 'create', 'create for others', 'update', 'delete']);
        $this->givePermissionToRole($programmer, $administerPermission);
        $this->givePermissionToRole($programmer, $canImpersonatePermission);
        $this->givePermissionToRole($programmer, $canViewLogsPermission);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $this->giveActionPermissionsToRole($admin, 'users', ['list', 'create','update']);
        $this->giveActionPermissionsToRole($admin, 'expert-panels', ['list', 'create','update']);
        $this->giveActionPermissionsToRole($admin, 'working-groups', ['list', 'create','update']);
        $this->giveActionPermissionsToRole($admin, 'volunteer-statuses', ['list', 'update']);
        $this->giveActionPermissionsToRole($admin, 'lookups', ['list', 'create','update']);
        $this->giveActionPermissionsToRole($admin, 'attestations', ['list', 'create','update', 'delete']);
        $this->giveActionPermissionsToRole($admin, 'uploads', ['list', 'create', 'create for others','update', 'delete']);
        $this->givePermissionToRole($admin, $administerPermission);
        $this->givePermissionToRole($admin, $canImpersonatePermission);

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
