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
            'manage role',
            'create role',
            'edit role',
            'delete role',
            'manage permission',
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
                'created_by' => 0,
            ]
        );
        $companyPermissions = [
            'manage user',
            'create user',
            'edit user',
            'delete user',
            'manage role',
            'create role',
            'edit role',
            'delete role',
            'manage permission',
            'create permission',
            'edit permission',
            'delete permission',
            'manage lead stage',
            'create lead stage',
            'edit lead stage',
            'delete lead stage',
            'manage project stage',
            'create project stage',
            'edit project stage',
            'delete project stage',
            'manage expense',
            'create expense',
            'edit expense',
            'delete expense',
            'manage client',
            'create client',
            'edit client',
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
            'edit event',
            'delete event',
            'manage project',
            'create project',
            'edit project',
            'delete project',
            'manage invoice',
            'create invoice',
            'edit invoice',
            'delete invoice',
            'manage task',
            'create task',
            'edit task',
            'delete task',
            'manage milestone',
            'create milestone',
            'edit milestone',
            'delete milestone',
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
            'manage event',
            'create event',
            'manage project',
            'manage task',
            'create task',
            'manage invoice',
            'manage expense',
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

    public function makeCollaboratorRole($owner_id)
    {
        $permissions = [
            'manage expense',
            'create expense',
            'manage client',
            'manage contact',
            'create contact',
            'manage lead',
            'create lead',
            'manage event',
            'create event',
            'manage project',
            'manage task',
            'create task',
            'manage invoice',
            'create invoice',
            'edit invoice',
            'manage milestone',
            'create milestone',
            'manage timesheet',
            'create timesheet',
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
            'manage role',
            'create role',
            'edit role',
            'delete role',
            'manage permission',
            'create permission',
            'edit permission',
            'delete permission',
            'manage lead stage',
            'create lead stage',
            'edit lead stage',
            'delete lead stage',
            'manage project stage',
            'create project stage',
            'edit project stage',
            'delete project stage',
            'manage expense',
            'create expense',
            'edit expense',
            'delete expense',
            'manage client',
            'create client',
            'edit client',
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
            'edit event',
            'delete event',
            'manage project',
            'create project',
            'edit project',
            'delete project',
            'manage invoice',
            'create invoice',
            'edit invoice',
            'delete invoice',
            'manage task',
            'create task',
            'edit task',
            'delete task',
            'manage milestone',
            'create milestone',
            'edit milestone',
            'delete milestone',
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
        $this->makeCollaboratorRole($superAdmin->id);

    }
}
