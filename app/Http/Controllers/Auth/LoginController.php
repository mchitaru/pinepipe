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
        if($user->delete_status == 0)
        {
            auth()->logout();
        }

        if($user->type == 'company')
        {
            $free_plan = PaymentPlan::where('price', '=', '0.0')->first();
            if($user->plan != $free_plan->id)
            {
                if(date('Y-m-d') > $user->plan_expire_date)
                {
                    $user->plan             = $free_plan->id;
                    $user->plan_expire_date = null;
                    $user->save();

                    $projects=Project::where('created_by','=',\Auth::user()->creatorId())->get();
                    $users=User::where('created_by','=',\Auth::user()->creatorId())->where('type','!=','client')->get();
                    $clients=User::where('created_by','=',\Auth::user()->creatorId())->where('type','client')->get();

                    $projectCount=0;
                    foreach($projects as $project){
                        $projectCount++;
                        if($projectCount<=$free_plan->max_projects){
                            $project->is_active=1;
                            $project->save();
                        }else{
                            $project->is_active=0;
                            $project->save();
                        }
                    }

                    $userCount=0;
                    foreach($users as $user){
                        $userCount++;
                        if($userCount<=$free_plan->max_users){
                            $user->is_active=1;
                            $user->save();
                        }else{
                            $user->is_active=0;
                            $user->save();
                        }
                    }
                    $clientCount=0;
                    foreach($clients as $client){
                        $clientCount++;
                        if($clientCount<=$free_plan->max_clients){
                            $client->is_active=1;
                            $client->save();
                        }else{
                            $client->is_active=0;
                            $client->save();
                        }
                    }

                    return redirect()->route(RouteServiceProvider::HOME)->with('error', 'Your plan expired limit is over, please upgrade your plan');
                }
            }

        }

    }
}
