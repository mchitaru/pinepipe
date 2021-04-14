<?php

namespace App\Http\Controllers;

use App\User;
use App\Client;
use File;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Gate;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserDestroyRequest;

use Illuminate\Support\Arr;

class UsersController extends Controller
{

    public function index(Request $request)
    {
        Gate::authorize('viewAny', 'App\User');

        $user = \Auth::user();

        if (!$request->ajax())
        {
            return view('users.page');
        }

        $users = User::withoutGlobalScopes()
                        ->where(function ($query) use ($request) {
                            $query->where('name','like','%'.$request['filter'].'%')
                            ->orWhere('email','like','%'.$request['filter'].'%');
                        })
                        ->orWhereHas('subscriptions', function ($query) use ($request)
                        {
                            $query->where(function ($query) {
                                $query->where('ends_at', null)
                                ->orWhere('ends_at', '>=', now());
                            })
                            ->whereHas('plan', function ($query) use ($request)
                            {
                                $query->where('name','like','%'.$request['filter'].'%');
                            });
                        })
                        ->paginate(25, ['*'], 'user-page');

        return view('users.index', ['users' => $users])->render();
    }

    public function subscribers(Request $request)
    {
        Gate::authorize('viewAny', 'App\User');

        $user = \Auth::user();

        if (!$request->ajax())
        {
            return view('users.page');
        }

        $users = User::withoutGlobalScopes()
                        ->whereHas('subscriptions', function ($query)
                        {
                            $query->where('ends_at', null)
                                ->orWhere('ends_at', '>=', now());

                        })->where(function ($query) use ($request) {
                            $query->where('name','like','%'.$request['filter'].'%')
                            ->orWhere('email','like','%'.$request['filter'].'%');
                        })->orWhereHas('subscriptions', function ($query) use ($request)
                        {
                            $query->where(function ($query) {
                                $query->where('ends_at', null)
                                ->orWhere('ends_at', '>=', now());
                            })
                            ->whereHas('plan', function ($query) use ($request)
                            {
                                $query->where('name','like','%'.$request['filter'].'%');
                            });
                        })
                        ->paginate(25, ['*'], 'user-page');

        return view('users.index', ['users' => $users])->render();
    }

    public function create(Request $request)
    {
        Gate::authorize('create', 'App\User');

        $user  = \Auth::user();

        return view('users.create', compact(''));
    }

    public function store(UserStoreRequest $request)
    {
        Gate::authorize('create', 'App\User');

        $post = $request->validated();

        if(\Auth::user()->isSuperAdmin())
        {
            $user = User::createUser($post);

            $request->session()->flash('success', __('User successfully created.'));

            return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
        }

        if(!\Auth::user()->hasMaxUsers())
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
        Gate::authorize('update', $user);

        return view('users.edit', compact(''));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $post = $request->validated();

        $user->updateUser($post);

        $request->session()->flash('success', __('User successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }


    public function destroy(UserDestroyRequest $request, User $user)
    {
        Gate::authorize('delete', $user);

        if($request->ajax()){

            return view('helpers.destroy');
        }

        $user->delete();

        return Redirect::to(URL::previous())->with('success', __('User successfully deleted.'));
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);

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

    public function verify(Request $request, User $user)
    {
        Gate::authorize('delete', $user);

        if($request->ajax()){

            return view('helpers.undoable');
        }

        $user->sendEmailVerificationNotification();

        return Redirect::to(URL::previous())->with('success', __('Verification email successfully sent.'));
    }
}
