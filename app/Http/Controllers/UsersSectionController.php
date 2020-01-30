<?php

namespace App\Http\Controllers;
use App\User;
use Spatie\Permission\Models\Role;

use Illuminate\Http\Request;

class UsersSectionController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        if(\Auth::user()->can('manage user'))
        {
            if(\Auth::user()->type == 'super admin')
            {
                $users = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'company')->get();
            }
            else
            {
                $users = User::where('created_by', '=', $user->creatorId())->where('type', '!=', 'client')->get();
            }

            if(\Auth::user()->can('manage role'))
            {
                $roles = Role::where('created_by','=',\Auth::user()->creatorId())->get();
            }
    
            return view('sections.users.index', compact('users', 'roles'));
        }
        else
        {
            return redirect()->back();
        }

    }
}
