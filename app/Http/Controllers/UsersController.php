<?php

namespace App\Http\Controllers;

use App\User;
use App\Client;
use File;
use Illuminate\Http\Request;
use Session;
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
        if(\Auth::user()->can('viewAny', 'App\User'))
        {
            if (!$request->ajax())
            {
                return view('users.page');
            }

            if(\Auth::user()->type == 'super admin')
            {
                $users = User::withoutGlobalScopes()
                                ->where('type', '=', 'company')
                                ->where(function ($query) use ($request) {
                                    $query->where('name','like','%'.$request['filter'].'%')
                                    ->orWhere('email','like','%'.$request['filter'].'%');
                                })
                                ->paginate(25, ['*'], 'user-page');
            }
            else
            {
                $users = User::withoutGlobalScopes()
                                ->where(function ($query) use ($request) {
                                    $query->where('name','like','%'.$request['filter'].'%')
                                    ->orWhere('email','like','%'.$request['filter'].'%');
                                })
                                ->where('created_by', \Auth::user()->created_by)
                                ->orWhereIn('created_by', \Auth::user()->collaborators->pluck('id'))
                                ->orderBy($request['sort']?$request['sort']:'name', $request['dir']?$request['dir']:'asc')
                                ->paginate(25, ['*'], 'user-page');
            }

            return view('users.index', ['users' => $users])->render();
        }
        else
        {
            return redirect()->back();
        }

    }

    public function create(Request $request)
    {
        $user  = \Auth::user();

        return view('users.create', compact(''));
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

        $url = redirect()->route('subscription')->getTargetUrl();
        return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
    }

    public function edit(Request $request, User $user)
    {
        return view('users.edit', compact(''));
    }


    public function update(UserUpdateRequest $request, User $user)
    {
        $post = $request->validated();

        if(\Auth::user()->type == 'super admin')
        {
            $user->updateCompany($post);
        }
        else
        {
            $user->updateUser($post);
        }

        $request->session()->flash('success', __('User successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }


    public function destroy(UserDestroyRequest $request, User $user)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $user->delete();

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
