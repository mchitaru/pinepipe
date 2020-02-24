<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Braintree_Transaction;
use App\PaymentPlan;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use App\User;
use Laravel\Cashier\Subscription;

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
    public function create(Request $request)
    {
        if(\Auth::user()->can('buy plan'))
        {    
            $plan_id = $request['plan_id'];
            $plan = PaymentPlan::find($plan_id);

            return view('subscriptions.create', compact('plan'));
        }
        else
        {
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $payload = $request->input('payload');
            $plan_id = $request->input('subscription');

            $plan = PaymentPlan::find($plan_id);

            if(!$plan){
                return response()->json(['success' => false]);
            }
            
            $nonce = $payload['nonce'];
    
            $user = \Auth::user();

            $subscription = $user->newSubscription('default', $plan->braintree_id)->create($nonce);

            $subscription->max_clients = $plan->max_clients;
            $subscription->max_projects = $plan->max_projects;
            $subscription->max_users = $plan->max_users;
            $subscription->max_space = $plan->max_space;

            $subscription->save();
    
            return response()->json(['success' => true]);
        } catch (\Exception $ex) {
            return response()->json(['success' => false]);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        \Auth::user()->subscription()->cancel();

        return Redirect::to(URL::previous() . "#subscription")->with('success', __('Subscription cancelled.'));
    }
}
