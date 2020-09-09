<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Permission;
use App\Role;

class PermissionsController extends Controller
{


    public function index()
    {
        if(\Auth::user()->can('view permission'))
        {
            $permissions = Permission::all();

            return view('permission.index')->with('permissions', $permissions);
        }
        else
        {
            return redirect()->back()->with('error', __('You dont have the right to perform this operation!'));
        }

    }

    public function create()
    {
        if(\Auth::user()->can('create permission'))
        {
            $roles = Role::where('created_by','=',\Auth::user()->created_by)->get();

            return view('permission.create')->with('roles', $roles);
        }
        else
        {
            return redirect()->back()->with('error', __('You dont have the right to perform this operation!'));
        }


    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create permission'))
        {
            $this->validate(
                $request, [
                'name' => 'required|max:40',
            ]
            );

            $name             = $request['name'];
            $permission       = new Permission();
            $permission->name = $name;

            $roles = $request['roles'];

            $permission->save();

            if(!empty($request['roles']))
            { //If one or more role is selected
                foreach($roles as $role)
                {
                    $r          = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record
                    $permission = Permission::where('name', '=', $name)->first(); //Match input //permission to db record
                    $r->givePermissionTo($permission);
                }
            }

            return redirect()->route('permissions.index')->with(
                    'success', 'Permission ' . $permission->name . ' added!'
                );
        }
        else
        {
            return redirect()->back()->with('error', __('You dont have the right to perform this operation!'));
        }


    }


    public function edit(Permission $permission)
    {
        if(\Auth::user()->can('edit permission'))
        {
            $roles = Role::where('created_by','=',\Auth::user()->created_by)->get();

            return view('permission.edit', compact('roles', 'permission'));
        }
        else
        {
            return redirect()->back()->with('error', __('You dont have the right to perform this operation!'));
        }


    }


    public function update(Request $request, Permission $permission)
    {
        if(\Auth::user()->can('edit permission'))
        {
            $permission = Permission::findOrFail($permission['id']);
            $this->validate(
                $request, [
                            'name' => 'required|max:40',
                        ]
            );
            $input = $request->all();
            $permission->fill($input)->save();

            return redirect()->route('permissions.index')->with(
                'success', 'Permission ' . $permission->name . ' updated!'
            );
        }
        else
        {
            return redirect()->back()->with('error', __('You dont have the right to perform this operation!'));
        }


    }

    public function destroy($id)
    {
        if(\Auth::user()->can('delete permission'))
        {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return redirect()->route('permissions.index')->with(
                'success', 'Permission deleted!'
            );
        }
        else
        {
            return redirect()->back()->with('error', __('You dont have the right to perform this operation!'));
        }



    }
}
