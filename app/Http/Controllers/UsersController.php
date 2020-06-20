<?php

namespace App\Http\Controllers;

use App\User;
use App\Client;
use File;
use Illuminate\Http\Request;
use Session;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserDestroyRequest;

use Illuminate\Support\Arr;

class UsersController extends Controller
{

    public function index(Request $request)
    {
        $user = \Auth::user();
        if(\Auth::user()->can('view user'))
        {
            if(\Auth::user()->type == 'super admin')
            {
                $users = User::withTrashed()
                                ->where('type', '=', 'company')
                                ->where(function ($query) use ($request) {
                                    $query->where('name','like','%'.$request['filter'].'%')
                                    ->orWhere('email','like','%'.$request['filter'].'%');
                                })
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
        $user  = \Auth::user();

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

        $roles = $roles->pluck('name', 'id');

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

    public function store(UserStoreRequest $request)
    {
        $post = $request->validated();

        if(\Auth::user()->type == 'super admin')
        {
            $user = User::createCompany($post);

            $request->session()->flash('success', __('User successfully created.'));

            return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
        }

        if(\Auth::user()->checkUserLimit())
        {
            $user = User::createUser($post);

            $request->session()->flash('success', __('User successfully created.'));

            return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
        }
        else
        {
            $request->session()->flash('error', __('Your have reached your user limit. Please upgrade your subscription to add more users!'));
        }

        $url = redirect()->route('profile.edit', \Auth::user()->handle())->getTargetUrl().'/#subscription';
        return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
    }

    public function edit(Request $request, User $user)
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

        $roles = $roles->pluck('name', 'id');

        $clients = Client::where('created_by', '=', $user->creatorId())
                            ->orderBy('id', 'DESC')
                            ->get()
                            ->pluck('name', 'id');

        if(isset($request['role'])){
            $role = Role::findById($request['role']);
        }else{
            $role = $user->roles()->first();
        }

        return view('users.edit', compact('user', 'roles', 'role', 'clients'));
    }


    public function update(UserUpdateRequest $request, $user_id)
    {
        if($request->ajax() && $request->isMethod('patch') && !isset($request['archived']))
        {
            return view('helpers.archive');
        }

        $post = $request->validated();

        if($request->isMethod('put'))
        {
            $user = User::find($user_id);

            if(\Auth::user()->type == 'super admin')
            {
                $user->updateCompany($post);
            }
            else
            {
                $user->updateUser($post);
            }

            $request->session()->flash('success', __('User successfully updated.'));
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
            }
        }

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }


    public function destroy(UserDestroyRequest $request, User $user)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $user->forceDelete();

        return Redirect::to(URL::previous())->with('success', __('User successfully deleted.'));
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

    public function refresh(Request $request, $user_id)
    {
        $request->flash();

        if($user_id)
        {
            $user = User::find($user_id);
            return $this->edit($request, $user);
        }

        return $this->create($request);

    }
}
