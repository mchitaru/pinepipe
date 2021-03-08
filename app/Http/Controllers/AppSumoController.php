<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\NewUserMail;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\AppSumoRequest;
use App\SubscriptionPlan;
use App\Subscription;
use App\PromoCode;
use Carbon\Carbon;

class AppSumoController extends Controller
{
    protected function create()
    {
        return view('auth.appsumo');
    }

    protected function store(AppSumoRequest $request)
    {
        $post = $request->validated();

        if(PromoCode::where('code', $post['code'])->first() != NULL &&
            Subscription::where('paddle_subscription', $post['code'])->first() == NULL){

            $user = User::create(
                [
                    'name' => $post['name'],
                    'email' => $post['email'],
                    'password' => Hash::make($post['password']),
                    'type' => 'company'
                ]
            );

            $location = geoip(\Request::ip());
            $user->setLocale($location);

            $user->initCompanyDefaults();

            $planId = '645244';
            $plan = SubscriptionPlan::where('paddle_id', $planId)->first();

            $subscription = Subscription::create(['user_id' => $user->id,
                                                    'paddle_subscription' => $post['code'],
                                                    'paddle_plan' => $planId,
                                                    'trial_ends_at' => Carbon::now(),
                                                    'ends_at' => null,
                                                    'max_clients' => $plan->max_clients,
                                                    'max_projects' => $plan->max_projects,
                                                    'max_users' => $plan->max_users,
                                                    'max_space' => $plan->max_space]);


            $user->sendEmailVerificationNotification();

            auth()->login($user);

            Mail::to('team@pinepipe.com')
                    ->queue(new NewUserMail($post['name'], $post['email']));

            return redirect()->route('home');
        }

        return redirect()->back()->with('error', __('The AppSumo code you used is invalid!'));
    }
}
