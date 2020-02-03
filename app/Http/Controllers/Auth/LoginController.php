<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PaymentPlan;
use App\Project;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if(!$user->delete_status)
        {
            auth()->logout();
        }

        if($user->type == 'company')
        {
            $free_plan = PaymentPlan::where('price', '=', '0.0')->first();

            if($user->plan_id != $free_plan->id)//to do: move to cron!!
            {
                if(date('Y-m-d') > $user->plan_expire_date)
                {
                    $user->assignPlan($free_plan->id);

                    return redirect()->route(RouteServiceProvider::HOME)->with('error', 'Your payment plan expired. Please upgrade to continue using all the features!');
                }
            }

        }

    }
}
