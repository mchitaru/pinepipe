<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class UserRolesController extends UsersSectionController
{

    public function create()
    {
        if(\Auth::user()->can('create role')){
            $user = \Auth::user();
            if($user->type == 'super admin')
            {
                $permissions = Permission::all()->pluck('name', 'id')->toArray();
            }else{
                $permissions = new Collection();
                foreach ($user->roles as $role) {
                    $permissions = $permissions->merge($role->permissions);
                }
                $permissions = $permissions->pluck('name','id')->toArray();
            }
            return view('roles.create', ['permissions' => $permissions]);
        }else
        {
            return Redirect::to(URL::previous() . "#roles")->with('error', __('Permission denied.'));
        }

    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create role')){
            $this->validate(
                $request, [
                            'name' => 'required|max:100|unique:roles,name,NULL,id,created_by,'.\Auth::user()->creatorId(),
                            'permissions' => 'required',
                        ]
            );
            $name        = $request['name'];
            $role        = new Role();
            $role->name  = $name;
            $role->created_by=\Auth::user()->creatorId();
            $permissions = $request['permissions'];
            $role->save();

            foreach($permissions as $permission)
            {
                $p    = Permission::where('id', '=', $permission)->firstOrFail();
                $role->givePermissionTo($p);
            }

            return Redirect::to(URL::previous() . "#roles")->with('success', __('Role successfully created.'));
        }else
        {
            return Redirect::to(URL::previous() . "#roles")->with('error', __('Permission denied.'));
        }


    }

    public function edit(Role $role)
    {
        if(\Auth::user()->can('edit role')){

            $user = \Auth::user();
            if($user->type == 'super admin')
            {
                $permissions = Permission::all()->pluck('name', 'id')->toArray();
            }else{
                $permissions = new Collection();
                foreach ($user->roles as $role1) {
                    $permissions = $permissions->merge($role1->permissions);
                }
                $permissions = $permissions->pluck('name','id')->toArray();
            }

            return view('roles.edit', compact('role', 'permissions'));
        }else
        {
            return Redirect::to(URL::previous() . "#roles")->with('error', __('Permission denied.'));
        }


    }

    public function update(Request $request, Role $role)
    {
        if(\Auth::user()->can('edit role')){
            $this->validate(
                $request, [
                            'name' => 'required|max:100|unique:roles,name,'. $role['id'].',id,created_by,'.\Auth::user()->creatorId(),
                            'permissions' => 'required',
                        ]
            );

            $input       = $request->except(['permissions']);
            $permissions = $request['permissions'];
            $role->fill($input)->save();

            $p_all = Permission::all();

            foreach($p_all as $p)
            {
                $role->revokePermissionTo($p);
            }

            foreach($permissions as $permission)
            {
                $p = Permission::where('id', '=', $permission)->firstOrFail();
                $role->givePermissionTo($p);
            }

            return Redirect::to(URL::previous() . "#roles")->with('success', __('Role successfully updated.'));
        }else
        {
            return Redirect::to(URL::previous() . "#roles")->with('error', __('Permission denied.'));
        }

    }


    public function destroy(Request $request, Role $role)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        if(\Auth::user()->can('delete role')){
            $role->delete();

            return Redirect::to(URL::previous() . "#roles")->with('success', __('Role successfully deleted.'));
        }else
        {
            return Redirect::to(URL::previous() . "#roles")->with('error', __('Permission denied.'));
        }
        
    }
}
