<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Auth;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Arr;

class UserRolesController extends Controller
{
    private static $modules = [ 'contact', 'client', 'lead', 'project', 'task', 'timesheet', 'invoice', 'expense',
                                'lead stage', 'task stage', 'user', 'role'];

    public function index()
    {
        $user = \Auth::user();
        if(\Auth::user()->can('view permission'))
        {
            $defaultRoles = Role::where('created_by', 1)->orderBy('id', 'desc')->get();

            $roles = Role::where('created_by', \Auth::user()->creatorId())->get();

            foreach($defaultRoles as $defaultRole){

                $role = Arr::first($roles, function ($value, $key) use($defaultRole) {

                    return $value->name == $defaultRole->name;
                });

                if(!$role) {
                    $roles->prepend($defaultRole);
                }
            }

            return view('roles.page', compact('roles'));
        }
        else
        {
            return redirect()->back();
        }

    }

    public function create()
    {
        if(\Auth::user()->can('create permission')){

            $user = \Auth::user();
            $modules = UserRolesController::$modules;

            if($user->type == 'super admin')
            {
                $modules[] = 'language';
                $modules[] = 'permission';
                $modules[] = 'system settings';

                $permissions = Permission::all()->pluck('name', 'id')->toArray();
            }else{
                $permissions = new Collection();
                foreach ($user->roles as $role) {
                    $permissions = $permissions->merge($role->permissions);
                }
                $permissions = $permissions->pluck('name','id')->toArray();
            }

            return view('roles.create', compact('modules', 'permissions'));
        }else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }

    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create permission')){
            $this->validate(
                $request, [
                            'name' => 'required|not_in:super admin|max:100|unique:roles,name,NULL,id,created_by,'.\Auth::user()->creatorId(),
                            'permissions' => 'required',
                        ]
            );
            $name               = $request['name'];
            $role               = new Role();
            $role->name         = $name;
            $role->user_id      = \Auth::user()->id;
            $role->created_by   = \Auth::user()->creatorId();
            $permissions = $request['permissions'];
            $role->save();

            foreach($permissions as $permission)
            {
                $p    = Permission::where('id', '=', $permission)->firstOrFail();
                $role->givePermissionTo($p);
            }

            return Redirect::to(URL::previous())->with('success', __('Role successfully created.'));
        }else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }


    }

    public function edit(Role $role)
    {
        if(\Auth::user()->can('edit permission')){

            $modules = UserRolesController::$modules;
            $user = \Auth::user();

            if($user->type == 'super admin')
            {
                $modules[] = 'language';
                $modules[] = 'permission';
                $modules[] = 'system settings';

                $permissions = Permission::all()->pluck('name', 'id')->toArray();
            }else{
                $permissions = new Collection();
                foreach ($user->roles as $role1) {
                    $permissions = $permissions->merge($role1->permissions);
                }
                $permissions = $permissions->pluck('name','id')->toArray();
            }

            return view('roles.edit', compact('modules', 'role', 'permissions'));
        }else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }


    }

    public function update(Request $request, Role $role)
    {
        if(\Auth::user()->can('edit permission')){
            $this->validate(
                $request, [
                            'name' => 'required|not_in:super admin|max:100|unique:roles,name,'. $role['id'].',id,created_by,'.\Auth::user()->creatorId(),
                            'permissions' => 'required',
                        ]
            );

            $input       = $request->except(['permissions']);

            if($role->name == 'client')
            {
                $input['name'] = $role->name;
            }

            $permissions = $request['permissions'];

            $defaultRole = $role;
            $users = [];

            if($role->created_by != \Auth::user()->creatorId()){

                $role               = $role->replicate();

                $role->user_id      = \Auth::user()->id;
                $role->created_by   = \Auth::user()->creatorId();

                $users = User::withTrashed()
                            ->where('created_by', '=', \Auth::user()->creatorId())->get();
            }

            $role->fill($input);
            $role->save();

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

            //if we made a copy, assign users to new role
            if($role != $defaultRole){

                foreach($users as $user){

                    if($user->hasRole(array($defaultRole->id))) {

                        $user->roles()->sync(array($role->id));
                    }
                }
            }

            return Redirect::to(URL::previous())->with('success', __('Role successfully updated.'));
        }else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }

    }


    public function destroy(Request $request, Role $role)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        if($role->created_by != 1 && \Auth::user()->can('delete permission')){

            $defaultRole = Role::where('created_by', 1)
                                    ->where('name', $role->name)
                                    ->first();

            $users = User::withTrashed()
                ->where('created_by', '=', \Auth::user()->creatorId())->get();

            foreach($users as $user){

                if($user->hasRole(array($role->id))) {

                    $user->roles()->sync(array($defaultRole->id));
                }
            }

            $role->delete();

            return Redirect::to(URL::previous())->with('success', __('Role successfully deleted.'));
        }else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }

    }
}
