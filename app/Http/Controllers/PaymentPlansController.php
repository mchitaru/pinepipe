<?php

namespace App\Http\Controllers;

use App\PaymentPlan;
use App\Utility;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;

class PaymentPlansController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage plan'))
        {
            $plans = PaymentPlan::get();
            return view('plan.index', compact('plans'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create plan'))
        {
            $arrDuration = PaymentPlan::$arrDuration;

            return view('plan.create', compact('arrDuration'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create plan'))
        {
            if(empty(env('STRIPE_KEY')) || empty(env('STRIPE_SECRET')))
            {
                return redirect()->back()->with('error', __('Please set stripe api key & secret key for add new plan.'));
            }
            else
            {

                $validation                 = [];
                $validation['name']         = 'required|unique:plans';
                $validation['price']        = 'required|numeric|min:0';
                $validation['duration']     = 'required';
                $validation['max_users']    = 'required|numeric';
                $validation['max_clients']  = 'required|numeric';
                $validation['max_projects'] = 'required|numeric';
                $request->validate($validation);
                $post = $request->all();

                if(PaymentPlan::create($post))
                {
                    return redirect()->back()->with('success', __('PaymentPlan Successfully created.'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function edit($plan_id)
    {
        if(\Auth::user()->can('edit plan'))
        {
            $arrDuration = PaymentPlan::$arrDuration;
            $plan        = PaymentPlan::find($plan_id);

            return view('plan.edit', compact('plan', 'arrDuration'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, $plan_id)
    {
        if(\Auth::user()->can('edit plan'))
        {
            if(empty(env('STRIPE_KEY')) || empty(env('STRIPE_SECRET')))
            {
                return redirect()->back()->with('error', __('Please set stripe api key & secret key to add new plan.'));
            }
            else
            {
                $plan = PaymentPlan::find($plan_id);
                if(!empty($plan))
                {
                    $validation                 = [];
                    $validation['name']         = 'required|unique:plans,name,' . $plan_id;
                    $validation['price']        = 'required|numeric|min:0';
                    $validation['duration']     = 'required';
                    $validation['max_users']    = 'required|numeric';
                    $validation['max_clients']  = 'required|numeric';
                    $validation['max_projects'] = 'required|numeric';

                    $request->validate($validation);

                    $post = $request->all();

                    if($plan->update($post))
                    {
                        return redirect()->back()->with('success', __('PaymentPlan Successfully updated.'));
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('Something is wrong.'));
                    }
                }
                else
                {
                    return redirect()->back()->with('error', __('PaymentPlan not found.'));
                }
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function userPlan(Request $request)
    {

        $planID  = \Illuminate\Support\Facades\Crypt::decrypt($request->code);
        $plan    = PaymentPlan::find($planID);

        if($plan)
        {
            if($plan->price <= 0)
            {
                \Auth::user()->assignPlan($plan->id);

                return redirect()->route('plans.index')->with('success', __('PaymentPlan Successfully activated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('PaymentPlan not found.'));
        }
    }
}
