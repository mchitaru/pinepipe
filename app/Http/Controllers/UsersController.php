<?php

namespace App\Http\Controllers;

use App\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class UsersController extends Controller
{

    public function index()
    {
        $user = \Auth::user();
        if(\Auth::user()->can('manage user'))
        {
            if(\Auth::user()->type == 'super admin')
            {
                $users = User::withTrashed()
                                ->where('created_by', '=', $user->creatorId())
                                ->where('type', '=', 'company')
                                ->paginate(25, ['*'], 'user-page');
            }
            else
            {
                $users = User::withTrashed()
                                ->where('created_by', '=', $user->creatorId())
                                ->where('type', '!=', 'client')
                                ->paginate(25, ['*'], 'user-page');
            }
    
            return view('users.page', compact('users'));
        }
        else
        {
            return redirect()->back();
        }

    }

    public function create()
    {
        $user  = \Auth::user();
        $roles = Role::where('created_by', '=', $user->creatorId())->get()->pluck('name', 'id');
        if(\Auth::user()->can('create user'))
        {
            return view('users.create', compact('roles'));
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
                $user['plan_id']    = PaymentPlan::first()->id;
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
 
                if(\Auth::user()->checkUserLimit())
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
                    return Redirect::to(URL::previous() . "#users")->with('error', __('Your have reached your user limit. Please upgrade your plan to add more users!'));
                }
            }


            return Redirect::to(URL::previous() . "#users")->with('success', __('User successfully created.'));
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
            return view('users.edit', compact('user', 'roles'));
        }
        else
        {
            return Redirect::to(URL::previous() . "#users")->with('error', __('Permission denied.'));
        }

    }


    public function update(Request $request, $user_id)
    {
        if($request->ajax())
        {
            return view('helpers.archive');
        }

        if($request->isMethod('put'))
        {
            $user = User::find($user_id);
            
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

                    return Redirect::to(URL::previous() . "#users")->with('success', __('User successfully updated.'));
                }
            }
        }
        else
        {    
            //soft delete
            if(\Auth::user()->can('delete user'))
            {
                $user = User::withTrashed()->find($user_id);
                
                if(!$user->trashed())
                {    
                    $user->delete();
                    $request->session()->flash('success', __('User successfully deleted.'));
                }
                else
                {
                    $user->restore();
                    $request->session()->flash('success', __('User successfully restored.'));
                }
        
                return Redirect::to(URL::previous() . "#users");
            }
        }

        return redirect()->back();
    }


    public function destroy(Request $request, User $user)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        if(\Auth::user()->can('delete user'))
        {
            $user->delete();
            $user->destroyUserProjectInfo($user->id);
            $user->removeUserLeadInfo($user->id);
            $user->destroyUserNotesInfo($user->id);
            $user->removeUserExpenseInfo($user->id);
            $user->removeUserTaskInfo($user->id);
            $user->destroyUserTaskAllInfo($user->id);

            return Redirect::to(URL::previous() . "#users")->with('success', __('User successfully deleted.'));
        }
        else
        {
            return redirect()->back();
        }
    }

    public function show(User $user)
    {
        return redirect()->back();
    }

    public function readNotifications()
    {
        \Auth::user()->unreadNotifications->markAsRead();
        
        return response('');
    }

    public function authRouteAPI(Request $request){
        return $request->user();
     }
}
