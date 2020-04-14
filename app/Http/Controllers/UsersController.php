<?php

namespace App\Http\Controllers;

use App\User;
use App\Client;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class UsersController extends Controller
{

    public function index(Request $request)
    {
        $user = \Auth::user();
        if(\Auth::user()->can('manage user'))
        {
            if(\Auth::user()->type == 'super admin')
            {
                $users = User::withTrashed()
                                ->where('type', '=', 'company')
                                ->where(function ($query) use ($request) {
                                    $query->where('name','like','%'.$request['filter'].'%')
                                    ->orWhere('email','like','%'.$request['filter'].'%');
                                })
                                ->orderBy($request['sort']?$request['sort']:'name', $request['dir']?$request['dir']:'asc')
                                ->paginate(25, ['*'], 'user-page');
            }
            else
            {
                $users = User::withTrashed()
                                ->where('created_by', '=', $user->creatorId())
                                ->where(function ($query) use ($request) {
                                    $query->where('name','like','%'.$request['filter'].'%')
                                    ->orWhere('email','like','%'.$request['filter'].'%');
                                })
                                ->orderBy($request['sort']?$request['sort']:'name', $request['dir']?$request['dir']:'asc')
                                ->paginate(25, ['*'], 'user-page');
            }
    
            if ($request->ajax()) 
            {
                return view('users.index', ['users' => $users])->render();  
            }

            return view('users.page', compact('users'));
        }
        else
        {
            return redirect()->back();
        }

    }

    public function create(Request $request)
    {
        if(\Auth::user()->can('create user'))
        {
            $user  = \Auth::user();
            $roles = Role::where(function ($query) use ($user) {
                                $query->where('created_by', '=', 1)
                                    ->orWhere('created_by', '=', $user->creatorId());
                            })
                            ->orderBy('id', 'DESC')
                            ->get()
                            ->pluck('name', 'id');
                            
            $clients = Client::where('created_by', '=', $user->creatorId())
                            ->orderBy('id', 'DESC')
                            ->get()
                            ->pluck('name', 'id');

            $role = null;

            if(isSet($request['role'])){
                $role = Role::find($request['role']);
            }
    
            return view('users.create', compact('role', 'roles', 'clients'));
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
                                'client_id' => 'required_if:role,client'
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
                    return Redirect::to(route('profile.show').'/#subscription')->with('error', __('Your have reached your user limit. Please upgrade your subscription to add more users!'));
                }
            }


            return Redirect::to(URL::previous())->with('success', __('User successfully created.'));
        }
        else
        {
            return redirect()->back();
        }

    }

    public function edit(User $user)
    {
            $roles = Role::where(function ($query) use ($user) {
                                $query->where('created_by', '=', 1)
                                        ->orWhere('created_by', '=', \Auth::user()->creatorId());
                            })
                            ->orderBy('id', 'DESC')
                            ->get()
                            ->pluck('name', 'id');

        if(\Auth::user()->can('edit user'))
        {
            return view('users.edit', compact('user', 'roles'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
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

                    return Redirect::to(URL::previous())->with('success', __('User successfully updated.'));
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
        
                return Redirect::to(URL::previous());
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

            return Redirect::to(URL::previous())->with('success', __('User successfully deleted.'));
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

    public function refresh(Request $request)
    {
        $request->flash();

        return $this->create($request);
    } 
}
