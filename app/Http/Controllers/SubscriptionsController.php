<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubscriptionPlan;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\Subscription;

use ProtoneMedia\LaravelPaddle\Paddle;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $plan_id)
    {
        if(\Auth::user()->type == 'company')
        {
            $plan = SubscriptionPlan::find($plan_id);

            $payload = [
                'product_id' => $plan->paddle_id,
                'customer_email' => \Auth::user()->email,
                'passthrough' => ['user_id' => \Auth::user()->id,
                                    'plan_id' => $plan->id],
            ];

            $paddleResponse = Paddle::product()
                ->generatePayLink($payload)
                ->send();

            return Redirect::to($paddleResponse['url']);
        }
        else
        {
            $request->session()->flash('error', __('There was an error processing your payment request! If this continues, please contact our support team'));
            return redirect()->route('profile.edit', \Auth::user()->id)->getTargetUrl().'/#subscription';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Subscription $subscription)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $payload = [
            'subscription_id' => $subscription->paddle_subscription,
        ];

        Paddle::subscription()->cancelUser($payload)->send();

        $request->session()->flash('success', __('Subscription cancelled'));
        return redirect()->route('profile.edit', \Auth::user()->id)->getTargetUrl().'/#subscription';
    }
}
