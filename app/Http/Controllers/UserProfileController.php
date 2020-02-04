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

class UserProfileController extends Controller
{
    public function show()
    {
        $user = \Auth::user();
        $plans = PaymentPlan::get();
        $settings = \Auth::user()->settings();

        return view('users.profile', compact('user', 'plans', 'settings'));
    }

    public function update(UserProfileRequest $request)
    {
        $post = $request->validated();

        $user = \Auth::user();
        if($request->hasFile('avatar'))
        {
            $path = Helpers::storePublicFile($request->file('avatar'));
            $user->avatar = $path;
        }

        $user->name  = $post['name'];
        $user->email = $post['email'];
        $user->save();

        return Redirect::to(URL::previous() . "#profile")->with('success', __('Profile updated successfully.'));
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
