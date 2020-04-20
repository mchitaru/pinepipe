<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function makeSuperAdminRole()
    {
        // Super admin

        $superAdminRole        = Role::create(
            [
                'name' => 'super admin',
                'created_by' => 0,
            ]
        );
        $superAdminPermissions = [
            'manage user',
            'create user',
            'edit user',
            'delete user',
            'manage language',
            'create language',
            'manage account',
            'edit account',
            'change password account',
            'manage system settings',
            'manage stripe settings',
            'manage role',
            'create role',
            'edit role',
            'delete role',
            'manage permission',
            'create permission',
            'edit permission',
            'delete permission',
            'manage plan',
            'create plan',
            'edit plan',
        ];
        foreach($superAdminPermissions as $ap)
        {
            $permission = Permission::findByName($ap);
            $superAdminRole->givePermissionTo($permission);
        }

        return $superAdminRole;
    }

    public function makeCompanyRole($owner_id)
    {
        // company
        $companyRole        = Role::create(
            [
                'name' => 'company',
                'created_by' => 0,
            ]
        );
        $companyPermissions = [
            "manage user",
            "create user",
            "edit user",
            "delete user",
            "manage language",
            "manage account",
            "edit account",
            "change password account",
            "manage role",
            "create role",
            "edit role",
            "delete role",
            "manage company settings",
            "manage lead stage",
            "create lead stage",
            "edit lead stage",
            "delete lead stage",
            "manage project stage",
            "create project stage",
            "edit project stage",
            "delete project stage",
            "manage lead source",
            "create lead source",
            "edit lead source",
            "delete lead source",
            "manage expense",
            "create expense",
            "edit expense",
            "delete expense",
            "manage client",
            "create client",
            "edit client",
            "show client",
            "delete client",
            'manage contact',
            'create contact',
            'edit contact',
            'delete contact',
            "manage lead",
            "create lead",
            "edit lead",
            "delete lead",
            'manage event',
            'create event',
            'show event',
            'edit event',
            'delete event',
            "manage project",
            "create project",
            "edit project",
            "delete project",
            'client permission project',
            'invite user project',
            "manage item",
            "create item",
            "edit item",
            "delete item",
            "show project",
            "manage tax",
            "create tax",
            "edit tax",
            "delete tax",
            "manage invoice",
            "create invoice",
            "edit invoice",
            "delete invoice",
            "show invoice",
            "manage expense category",
            "create expense category",
            "edit expense category",
            "delete expense category",
            "manage payment",
            "create payment",
            "edit payment",
            "delete payment",
            "manage invoice item",
            "create invoice item",
            "edit invoice item",
            "delete invoice item",
            "manage invoice payment",
            "create invoice payment",
            "manage task",
            "create task",
            "edit task",
            "delete task",
            "move task",
            'show task',
            'create milestone',
            'edit milestone',
            'delete milestone',
            'view milestone',
            'manage plan',
            'buy plan',
            'buy plan',
            'manage timesheet',
            'create timesheet',
            'edit timesheet',
            'delete timesheet'
        ];

        foreach($companyPermissions as $ap)
        {
            $permission = Permission::findByName($ap);
            $companyRole->givePermissionTo($permission);
        }

        return $companyRole;
    }

    public function makeClientRole($owner_id)
    {
        $permissions = [
            'manage account',
            'edit account',
            'change password account',
            'show project',
            'manage project',
            'manage invoice',
            'show invoice',
            'manage expense',
            'manage payment',
            'manage timesheet',
        ];

        $role               =   new Role();
        $role->name         =   'client';
        $role->created_by   =   $owner_id;
        $role->save();

        foreach($permissions as $ap)
        {
            $permission = Permission::findByName($ap);
            $role->givePermissionTo($permission);
        }

        return $role;
    }

    public function makeEmployeeRole($owner_id)
    {
        $permissions = [
            'manage account',
            'change password account',
            'edit account',
            'manage client',
            'create client',
            'edit client',
            'show client',
            'delete client',
            'manage contact',
            'create contact',
            'edit contact',
            'delete contact',
            'manage lead',
            'create lead',
            'edit lead',
            'delete lead',
            'create event',
            'manage event',
            'edit event',
            'show event',
            'manage project',
            'show project',
            'create task',
            'manage task',
            'move task',
            'show task',
            'edit task',
            'manage lead',
            'create timesheet',
            'manage timesheet',
            'edit timesheet',
            'delete timesheet',
            'manage expense',
            'create expense',
            'edit expense',
            'delete expense',
        ];

        $role               =   new Role();
        $role->name         =   'collaborator';
        $role->created_by   =   $owner_id;
        $role->save();

        foreach($permissions as $ap)
        {
            $permission = Permission::findByName($ap);
            $role->givePermissionTo($permission);
        }

        return $role;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrPermissions = [
            'manage user',
            'create user',
            'edit user',
            'delete user',
            'manage language',
            'create language',
            'manage account',
            'edit account',
            'change password account',
            'manage system settings',
            'manage role',
            'create role',
            'edit role',
            'delete role',
            'manage permission',
            'create permission',
            'edit permission',
            'delete permission',
            'manage company settings',
            'manage stripe settings',
            'manage lead stage',
            'create lead stage',
            'edit lead stage',
            'delete lead stage',
            'manage project stage',
            'create project stage',
            'edit project stage',
            'delete project stage',
            'manage lead source',
            'create lead source',
            'edit lead source',
            'delete lead source',
            'manage expense',
            'create expense',
            'edit expense',
            'delete expense',
            'manage client',
            'create client',
            'edit client',
            'show client',
            'delete client',
            'manage contact',
            'create contact',
            'edit contact',
            'delete contact',
            'manage lead',
            'create lead',
            'edit lead',
            'delete lead',
            'manage event',
            'create event',
            'show event',
            'edit event',
            'delete event',
            'manage project',
            'create project',
            'edit project',
            'show project',
            'delete project',
            'client permission project',
            'invite user project',
            'manage item',
            'create item',
            'edit item',
            'delete item',
            'manage tax',
            'create tax',
            'edit tax',
            'delete tax',
            'manage invoice',
            'create invoice',
            'edit invoice',
            'delete invoice',
            'show invoice',
            'manage expense category',
            'create expense category',
            'edit expense category',
            'delete expense category',
            'manage payment',
            'create payment',
            'edit payment',
            'delete payment',
            'manage invoice item',
            'create invoice item',
            'edit invoice item',
            'delete invoice item',
            'manage invoice payment',
            'create invoice payment',
            'manage task',
            'create task',
            'edit task',
            'delete task',
            'move task',
            'show task',
            'create milestone',
            'edit milestone',
            'delete milestone',
            'view milestone',
            'manage plan',
            'create plan',
            'edit plan',
            'buy plan',
            'manage timesheet',
            'create timesheet',
            'edit timesheet',
            'delete timesheet'
        ];

        foreach($arrPermissions as $ap)
        {
            Permission::create(['name' => $ap]);
        }

        $superAdminRole = $this->makeSuperAdminRole();

        $superAdmin = User::create(
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('metallicarulz'),
                'type' => 'super admin',
                'lang' => 'en',
                'avatar' => '',
                'created_by' => 0,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        $this->makeCompanyRole($superAdmin->id);
        $this->makeClientRole($superAdmin->id);
        $this->makeEmployeeRole($superAdmin->id);

    }
}
