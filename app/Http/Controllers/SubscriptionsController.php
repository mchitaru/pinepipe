<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Braintree_Transaction;
use App\PaymentPlan;
use Illuminate\Support\Facades\URL;
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
            $user->newSubscription('default', $plan->name)->create($nonce);
    
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
    public function destroy($id)
    {
        //
    }
}
