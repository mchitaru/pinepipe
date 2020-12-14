<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubscriptionPlan;
use App\User;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserProfileDestroyRequest;
use App\Http\Requests\UserUnsubscribeRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

use App\Currency;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        $companySettings = $user->companySettings;
        $companyName = $companySettings ? $companySettings->name : null;
        $companyLogo = $companySettings ? $companySettings->media('logos')->first() : null;

        return view('users.profile.show', compact('user', 'companySettings', 'companyName', 'companyLogo'));
    }

    public function subscription(Request $request)
    {
        $request->session()->reflash();

        return Redirect::to(route('profile.edit').'/#subscription');
    }

    public function collaborators(Request $request)
    {
        $request->session()->reflash();

        return Redirect::to(route('profile.edit').'/#collaborators');
    }

    public function checkout(Request $request)
    {
        if(!empty($request['checkout'])){

            return redirect()->route('home')
                    ->with('success', __('Your subscription is now activated. Thank you for your payment!'))
                    ->with('checkout', $request['checkout']);
        }

        return redirect()->route('profile.edit')
                    ->with('error', __('There was a problem processing your payment. Please try again or contact us!'));
    }

    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        if(!$user->subscribed()){
            $user_plan = SubscriptionPlan::first();
        }else{
            $user_plan = SubscriptionPlan::where('paddle_id', $user->subscription()->paddle_plan)->first();
        }

        if($user->subscribed()){

            $plans = SubscriptionPlan::where('trial', 0)
                                        ->orWhere('paddle_id', $user->subscription()->paddle_plan)
                                        ->orWhere('id', 1)
                                        ->orderBy('sort','asc')
                                        ->orderBy('duration','asc')
                                        ->get();   

        }elseif($user->subscriptions->count()){

            $plans = SubscriptionPlan::where('trial', 0)
                                        ->orWhere('id', 1)
                                        ->orderBy('sort','asc')
                                        ->orderBy('duration','asc')
                                        ->get();   
        }else{

            $plans = SubscriptionPlan::where('trial', 1)
                                        ->orWhere('id', 1)
                                        ->orderBy('sort','asc')
                                        ->orderBy('duration','asc')->get();
        }

        $companySettings = $user->companySettings;
        $companyName = $companySettings ? $companySettings->name : null;
        $companyLogo = $companySettings ? $companySettings->media('logos')->first() : null;

        $currencies = Currency::get()->pluck('code', 'code');

        $locales = ['en' => 'English', 'ro' => 'Română'];

        $url = route('profile.update');

        $users = User::withoutGlobalScopes()
                        ->where('created_by', \Auth::user()->created_by)
                        ->orWhereIn('created_by', \Auth::user()->collaborators->pluck('id'))
                        ->paginate(25, ['*'], 'user-page');

        return view('users.profile.edit', compact('user', 'user_plan', 'plans', 'companySettings', 'companyName', 'companyLogo', 'currencies', 'locales', 'url', 'users'));
    }

    public function update(UserProfileRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $post = $request->validated();

        $user->fill($post);
        $user->save();

        if($request->hasFile('avatar')){

            $user->clearMediaCollection('logos');
            $file = $user->addMedia($request->file('avatar'))->toMediaCollection('logos');
        }

        return redirect()->back()->with('success', __('Profile updated successfully.'));
    }

    public function destroy(UserProfileDestroyRequest $request, User $user)
    {
        Gate::authorize('delete', $user);

        if($request->ajax()){

            return view('helpers.destroy');
        }

        \Auth::logout();

        $user->forceDelete();

        return Redirect::route('login')->with('success', __('Account successfully deleted.'));
    }

    public function editAuth()
    {
        return $this->edit(\Auth::user());
    }

    public function updateAuth(UserProfileRequest $request)
    {
        return $this->update($request, \Auth::user());
    }

    public function destroyAuth(UserProfileDestroyRequest $request)
    {
        return $this->destroy($request, \Auth::user());
    }

    public function passwordAuth(UserProfileRequest $request)
    {
        $user = \Auth::user();
        $post = $request->validated();

        if($user->password == null || Hash::check($post['current_password'], $user->password))
        {
            $user->password = Hash::make($post['new_password']);
            $user->save();

            return Redirect::to(URL::previous())->with('success', __('Password updated successfully.'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('Current pasword is incorrect.'));
        }
    }

    public function editUnsubscribe(User $user)
    {
        $url = URL::signedRoute('unsubscribe.update', ['user' => $user]);

        return view('users.profile.unsubscribe', compact('user', 'url'));
    }

    public function updateUnsubscribe(UserUnsubscribeRequest $request, User $user)
    {
        $post = $request->validated();

        $user->fill($post);
        $user->save();

        return redirect()->back()->with('success', __('Notifications updated successfully.'));
    }
}
