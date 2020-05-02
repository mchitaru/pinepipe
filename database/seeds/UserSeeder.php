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
            'view user',
            'create user',
            'edit user',
            'delete user',
            'view language',
            'create language',
            'view permission',
            'create permission',
            'edit permission',
            'delete permission',
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
                'user_id' => 0,
                'created_by' => 0,
            ]
        );
        $companyPermissions = [
            'view user',
            'create user',
            'edit user',
            'delete user',
            'view permission',
            'create permission',
            'edit permission',
            'delete permission',
            'create lead stage',
            'edit lead stage',
            'delete lead stage',
            'create task stage',
            'edit task stage',
            'delete task stage',
            'view all expenses',
            'view expense',
            'create expense',
            'edit expense',
            'delete expense',
            'view all clients',
            'view client',
            'create client',
            'edit client',
            'delete client',
            'view all contacts',
            'view contact',
            'create contact',
            'edit contact',
            'delete contact',
            'view all leads',
            'view lead',
            'create lead',
            'edit lead',
            'delete lead',
            'create event',
            'edit event',
            'delete event',
            'view all projects',
            'view project',
            'create project',
            'edit project',
            'delete project',
            'view all invoices',
            'view invoice',
            'create invoice',
            'edit invoice',
            'delete invoice',
            'view all tasks',
            'view task',
            'create task',
            'edit task',
            'delete task',
            'create milestone',
            'edit milestone',
            'delete milestone',
            'view all timesheets',
            'view timesheet',
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
            'create event',
            'edit event',
            'delete event',
            'view project',
            'view task',
            'create task',
            'view invoice',
            'view expense',
        ];

        $role               =   new Role();
        $role->name         =   'client';
        $role->user_id      =   $owner_id;
        $role->created_by   =   $owner_id;
        $role->save();

        foreach($permissions as $ap)
        {
            $permission = Permission::findByName($ap);
            $role->givePermissionTo($permission);
        }

        return $role;
    }

    public function makeCollaboratorRole($owner_id)
    {
        $permissions = [
            'view expense',
            'create expense',
            'edit expense',
            'view contact',
            'create contact',
            'edit contact',
            'view lead',
            'create lead',
            'edit lead',
            'create event',
            'edit event',
            'delete event',
            'view project',
            'view task',
            'create task',
            'edit task',
            'view invoice',
            'create invoice',
            'edit invoice',
            'create milestone',
            'edit milestone',
            'view timesheet',
            'create timesheet',
            'edit timesheet',
            'delete timesheet',
        ];

        $role               =   new Role();
        $role->name         =   'collaborator';
        $role->user_id      =   $owner_id;
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
            'view user',
            'create user',
            'edit user',
            'delete user',
            'view language',
            'create language',
            'view permission',
            'create permission',
            'edit permission',
            'delete permission',
            'create lead stage',
            'edit lead stage',
            'delete lead stage',
            'create task stage',
            'edit task stage',
            'delete task stage',
            'view all expenses',
            'view expense',
            'create expense',
            'edit expense',
            'delete expense',
            'view all clients',
            'view client',
            'create client',
            'edit client',
            'delete client',
            'view all contacts',
            'view contact',
            'create contact',
            'edit contact',
            'delete contact',
            'view all leads',
            'view lead',
            'create lead',
            'edit lead',
            'delete lead',
            'create event',
            'edit event',
            'delete event',
            'view all projects',
            'view project',
            'create project',
            'edit project',
            'delete project',
            'view all invoices',
            'view invoice',
            'create invoice',
            'edit invoice',
            'delete invoice',
            'view all tasks',
            'view task',
            'create task',
            'edit task',
            'delete task',
            'create milestone',
            'edit milestone',
            'delete milestone',
            'view all timesheets',
            'view timesheet',
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
        $this->makeCollaboratorRole($superAdmin->id);

    }
}
