<?php

namespace App\Http\Controllers;

use App\PaymentPlan;
use App\User;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use Spatie\Permission\Models\Role;
use App\Http\Helpers;

class UsersController extends UsersSectionController
{

    public function create()
    {
        $user  = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->get()->pluck('name', 'id');
        if(\Auth::user()->can('create user'))
        {
            return view('user.create', compact('roles'));
        }
        else
        {
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {

        if(\Auth::user()->can('create user'))
        {

            if(\Auth::user()->type == 'super admin')
            {
                $this->validate(
                    $request, [
                                'name' => 'required|max:120',
                                'email' => 'required|email|unique:users',
                                'password' => 'required|min:6',
                            ]
                );

                $user = new User();
                $user['name']   = $request->name;
                $user['email']   = $request->email;
                $user['password']   = Hash::make($request->password);
                $user['type']       = 'company';
                $user['lang']       = 'en';
                $user['created_by'] = \Auth::user()->creatorId();
                $user['plan']       = PaymentPlan::first()->id;
                $user->save();

                $role_r = Role::findByName('company');
                $user->initCompanyDefaults();
                $user->assignRole($role_r);
            }
            else
            {
                $this->validate(
                    $request, [
                                'name' => 'required|max:120',
                                'email' => 'required|email|unique:users',
                                'password' => 'required|min:6',
                                'role' => 'required',
                            ]
                );



                $objUser    = \Auth::user();
                $total_user = $objUser->countUsers();
                $plan       = PaymentPlan::find($objUser->plan);
                if($total_user < $plan->max_users || $plan->max_users == -1)
                {
                    $role_r                = Role::findById($request->role);
                    $request['password']   = Hash::make($request->password);
                    $request['type']       = $role_r->name;
                    $request['lang']       = 'en';
                    $request['created_by'] = \Auth::user()->creatorId();

                    $user = User::create($request->all());

                    $user->assignRole($role_r);
                }
                else
                {
                    return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
                }
            }


            return redirect()->route('users.index')->with(
                'success', 'User successfully added.'
            );
        }
        else
        {
            return redirect()->back();
        }

    }

    public function edit(User $user)
    {
        $roles = Role::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        if(\Auth::user()->can('edit user'))
        {
            return view('user.edit', compact('user', 'roles'));
        }
        else
        {
            return redirect()->back();
        }

    }


    public function update(Request $request, User $user)
    {

        if(\Auth::user()->can('edit user'))
        {
            if(\Auth::user()->type == 'super admin')
            {
                $this->validate(
                    $request, [
                                'name' => 'required|max:120',
                                'email' => 'required|email|unique:users,email,' . $user->id,
                            ]
                );
                $input = $request->all();
                $user->fill($input)->save();

                return redirect()->route('users.index')->with(
                    'success', 'User successfully updated.'
                );
            }
            else
            {
                $user = User::findOrFail($id);
                $this->validate(
                    $request, [
                                'name' => 'required|max:120',
                                'email' => 'required|email|unique:users,email,' . $user->id,
                                'role' => 'required',
                            ]
                );

                $role          = Role::findById($request->role);
                $input         = $request->all();
                $input['type'] = $role->name;
                $user->fill($input)->save();

                $roles[] = $request->role;
                $user->roles()->sync($roles);

                return redirect()->route('users.index')->with(
                    'success', 'User successfully updated.'
                );
            }
        }
        else
        {
            return redirect()->back();
        }
    }


    public function destroy(Request $request, User $user)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        if(\Auth::user()->can('delete user'))
        {
            if(\Auth::user()->type == 'super admin')
            {
                $user->delete_status = !$user->delete_status;
                $user->save();
            }
            else
            {
                $user->delete();
                $user->destroyUserProjectInfo($user->id);
                $user->removeUserLeadInfo($user->id);
                $user->destroyUserNotesInfo($user->id);
                $user->removeUserExpenseInfo($user->id);
                $user->removeUserTaskInfo($user->id);
                $user->destroyUserTaskAllInfo($user->id);
            }

            return redirect()->route('users.index')->with('success', __('User Deleted Successfully.'));
        }
        else
        {
            return redirect()->back();
        }
    }

    public function profile()
    {
        $user = \Auth::user();
        $plans = PaymentPlan::get();
        $settings = \Auth::user()->settings();

        return view('user.profile', compact('user', 'plans', 'settings'));
    }

    public function editprofile(Request $request)
    {
        $userDetail = \Auth::user();
        $user       = User::findOrFail($userDetail['id']);
        $this->validate(
            $request, [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                    ]
        );
        if($request->hasFile('avatar'))
        {
            $path = Helpers::storePublicFile($request->file('avatar'));
            $user['avatar'] = $path;
        }

        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();

        return redirect()->route('home')->with(
            'success', 'Profile successfully updated.'
        );
    }

    public function updatePassword(Request $request)
    {
        if(\Auth::user()->can('change password account'))
        {
            if(Auth::Check())
            {
                $request->validate(
                    [
                        'current_password' => 'required',
                        'new_password' => 'required|min:6',
                        'confirm_password' => 'required|same:new_password',
                    ]
                );
                $objUser          = Auth::user();
                $request_data     = $request->All();
                $current_password = $objUser->password;
                if(Hash::check($request_data['current_password'], $current_password))
                {
                    $user_id            = Auth::User()->id;
                    $obj_user           = User::find($user_id);
                    $obj_user->password = Hash::make($request_data['new_password']);;
                    $obj_user->save();
                    return redirect()->route('profile',$objUser->id)->with('success', __('Password updated successfully.'));
                }
                else
                {
                    return redirect()->route('profile',$objUser->id)->with('error', __('Please enter correct current password.'));
                }
            }
            else
            {
                return redirect()->route('profile',\Auth::user()->id)->with('error', __('Something is wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function authRouteAPI(Request $request){
        return $request->user();
     }
}
