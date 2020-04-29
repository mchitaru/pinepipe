<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubscriptionPlan;
use App\User;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\UserProfileRequest;
use Illuminate\Support\Facades\Hash;

use Money\Currencies\ISOCurrencies;
use Money\Currency;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = \Auth::user();

        if(!$user->subscribed()){
            $user_plan = SubscriptionPlan::first();
        }else{
            $user_plan = SubscriptionPlan::where('paddle_id', $user->subscription()->paddle_plan)->first();
        }

        $plans = SubscriptionPlan::get();

        $companySettings = \Auth::user()->companySettings;
        $companyName = $companySettings ? $companySettings->name : null;
        $companyLogo = $companySettings ? $companySettings->media('logos')->first() : null;

        $currencies = [];
        $isoCurrencies = new ISOCurrencies();

        foreach ($isoCurrencies as $currency) {
            $currencies[$currency->getCode()] = $currency->getCode();
        }

        return view('users.profile', compact('user', 'user_plan', 'plans', 'companySettings', 'companyName', 'companyLogo', 'currencies'));
    }

    public function update(UserProfileRequest $request, $tab)
    {
        $post = $request->validated();

        $user = \Auth::user();

        $user->fill($post);
        $user->save();

        if($request->hasFile('avatar')){

            $file = $user->addMedia($request->file('avatar'))->toMediaCollection('logos');
        }

        return Redirect::to(URL::previous())->with('success', __('Profile updated successfully.'));
    }

    public function password(UserProfileRequest $request)
    {
        $post = $request->validated();

        $user               = \Auth::user();

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
}
