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

use Money\Currencies\ISOCurrencies;
use Money\Currency;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        $companySettings = $user->companySettings;
        $companyName = $companySettings ? $companySettings->name : null;
        $companyLogo = $companySettings ? $companySettings->media('logos')->first() : null;

        return view('users.profile.show', compact('user', 'companySettings', 'companyName', 'companyLogo'));
    }

    public function edit(User $user)
    {
        if(\Auth::user()->id == $user->id) {

            if(!$user->subscribed()){
                $user_plan = SubscriptionPlan::first();
            }else{
                $user_plan = SubscriptionPlan::where('paddle_id', $user->subscription()->paddle_plan)->first();

                if($user_plan == null) {

                    //first user with trial
                    $user_plan = SubscriptionPlan::where('deal', 1)->first();
                }
            }

            $plans = SubscriptionPlan::orderBy('duration','asc')->get();

            $companySettings = $user->companySettings;
            $companyName = $companySettings ? $companySettings->name : null;
            $companyLogo = $companySettings ? $companySettings->media('logos')->first() : null;

            $currencies = [];
            $isoCurrencies = new ISOCurrencies();

            foreach ($isoCurrencies as $currency) {
                $currencies[$currency->getCode()] = $currency->getCode();
            }

            $locales = ['en' => 'English', 'ro' => 'Română'];

            $url = route('profile.update', \Auth::user()->handle());

            return view('users.profile.edit', compact('user', 'user_plan', 'plans', 'companySettings', 'companyName', 'companyLogo', 'currencies', 'locales', 'url'));

        }else{

            return Redirect::to(URL::previous())->with('error', __('Access forbidden!'));
        }
    }

    public function update(UserProfileRequest $request, User $user)
    {
        $post = $request->validated();

        $user->fill($post);
        $user->save();

        if($request->hasFile('avatar')){

            $user->clearMediaCollection('logos');
            $file = $user->addMedia($request->file('avatar'))->toMediaCollection('logos');
        }

        return redirect(route('profile.edit', $user->handle()))->with('success', __('Profile updated successfully.'));
    }

    public function destroy(UserProfileDestroyRequest $request, User $user)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        \Auth::logout();

        $user->forceDelete();

        return Redirect::route('login')->with('success', __('Account successfully deleted.'));
    }

    public function password(UserProfileRequest $request, User $user)
    {
        $post = $request->validated();

        if(Hash::check($post['current_password'], $user->password))
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
