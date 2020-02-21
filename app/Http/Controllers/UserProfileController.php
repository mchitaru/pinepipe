<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentPlan;
use App\User;
use App\Http\Helpers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\UserProfileRequest;
use Illuminate\Support\Facades\Hash;
use Braintree\Plan as BraintreePlan;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = \Auth::user();

        if(!$user->subscribed()){
            $user_plan = PaymentPlan::first();
        }else{
            $user_plan = PaymentPlan::where('braintree_id', $user->subscription()->braintree_plan)->first();
        }
        
        $arrPlans = ['Free'];
        $braintree_plans = BraintreePlan::all();

        foreach ($braintree_plans as $plan) 
        {
            $arrPlans[] = $plan->id;
        }

        //only select plans with a corresponding Braintree plan
        $plans = PaymentPlan::whereIn('braintree_id', $arrPlans)->get();

        $settings = \Auth::user()->settings();

        return view('users.profile', compact('user', 'user_plan', 'plans', 'settings'));
    }

    public function update(UserProfileRequest $request, $tab)
    {
        $post = $request->validated();

        $user = \Auth::user();

        $user->fill($post);

        if($request->hasFile('avatar'))
        {
            $path = Helpers::storePublicFile($request->file('avatar'));
            $user->avatar = $path;
        }

        $user->save();

        if($tab == 'personal'){
            return Redirect::to(URL::previous()."#personal")->with('success', __('Profile updated successfully.'));
        }else{
            return Redirect::to(URL::previous()."#notifications")->with('success', __('Profile updated successfully.'));
        }
    }

    public function password(UserProfileRequest $request)
    {
        $post = $request->validated();

        $user               = \Auth::user();
        
        if(Hash::check($post['current_password'], $user->password))
        {
            $user->password = Hash::make($post['new_password']);
            $user->save();

            return Redirect::to(URL::previous() . "#password")->with('success', __('Password updated successfully.'));
        }
        else
        {
            return Redirect::to(URL::previous() . "#password")->with('error', __('Current pasword is incorrect.'));
        }
    }
}
