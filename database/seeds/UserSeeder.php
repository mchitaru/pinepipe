<?php

use App\User;
use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;

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
            'view all article',
            'view article',
            'create article',
            'edit article',
            'delete article'
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
            'view all expense',
            'view expense',
            'create expense',
            'edit expense',
            'delete expense',
            'view all client',
            'view client',
            'create client',
            'edit client',
            'delete client',
            'view all contact',
            'view contact',
            'create contact',
            'edit contact',
            'delete contact',
            'view all lead',
            'view lead',
            'create lead',
            'edit lead',
            'delete lead',
            'create event',
            'edit event',
            'delete event',
            'view all project',
            'view project',
            'create project',
            'edit project',
            'delete project',
            'view all invoice',
            'view invoice',
            'create invoice',
            'edit invoice',
            'delete invoice',
            'view all task',
            'view task',
            'create task',
            'edit task',
            'delete task',
            'create milestone',
            'edit milestone',
            'delete milestone',
            'view all timesheet',
            'view timesheet',
            'create timesheet',
            'edit timesheet',
            'delete timesheet',
            'view all article',
            'view article',
            'create article',
            'edit article',
            'delete article'
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
            'view all article',
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
            'view timesheet',
            'create timesheet',
            'edit timesheet',
            'delete timesheet',
            'view all article',
            'view article',
            'create article',
            'edit article',
            'delete article'
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
            'view all expense',
            'view expense',
            'create expense',
            'edit expense',
            'delete expense',
            'view all client',
            'view client',
            'create client',
            'edit client',
            'delete client',
            'view all contact',
            'view contact',
            'create contact',
            'edit contact',
            'delete contact',
            'view all lead',
            'view lead',
            'create lead',
            'edit lead',
            'delete lead',
            'create event',
            'edit event',
            'delete event',
            'view all project',
            'view project',
            'create project',
            'edit project',
            'delete project',
            'view all task',
            'view task',
            'create task',
            'edit task',
            'delete task',
            'create milestone',
            'edit milestone',
            'delete milestone',
            'view all proposal',
            'view proposal',
            'create proposal',
            'edit proposal',
            'delete proposal',
            'view all contract',
            'view contract',
            'create contract',
            'edit contract',
            'delete contract',
            'view all invoice',
            'view invoice',
            'create invoice',
            'edit invoice',
            'delete invoice',
            'view all timesheet',
            'view timesheet',
            'create timesheet',
            'edit timesheet',
            'delete timesheet',
            'view all article',
            'view article',
            'create article',
            'edit article',
            'delete article'
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
                'avatar' => '',
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        $this->makeCompanyRole($superAdmin->id);
        $this->makeClientRole($superAdmin->id);
        $this->makeCollaboratorRole($superAdmin->id);

    }
}
