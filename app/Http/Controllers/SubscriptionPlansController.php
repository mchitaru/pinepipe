<?php

namespace App\Http\Controllers;

use App\SubscriptionPlan;
use App\Utility;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;

class SubscriptionPlansController extends Controller
{
    public function index()
    {
        if(\Auth::user()->type == 'super admin')
        {
            $plans = SubscriptionPlan::get();
            return view('plan.index', compact('plans'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->type == 'super admin')
        {
            return view('plan.create');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->type == 'super admin')
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
                $validation['duration']     = 'required|nullable';
                $validation['max_users']    = 'required|numeric';
                $validation['max_clients']  = 'required|numeric';
                $validation['max_projects'] = 'required|numeric';
                $request->validate($validation);
                $post = $request->all();

                if(SubscriptionPlan::create($post))
                {
                    return redirect()->back()->with('success', __('SubscriptionPlan Successfully created.'));
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
        if(\Auth::user()->type == 'super admin')
        {
            $plan        = SubscriptionPlan::find($plan_id);

            return view('plan.edit', compact('plan'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, $plan_id)
    {
        if(\Auth::user()->type == 'super admin')
        {
            if(empty(env('STRIPE_KEY')) || empty(env('STRIPE_SECRET')))
            {
                return redirect()->back()->with('error', __('Please set stripe api key & secret key to add new plan.'));
            }
            else
            {
                $plan = SubscriptionPlan::find($plan_id);
                if(!empty($plan))
                {
                    $validation                 = [];
                    $validation['name']         = 'required|unique:plans,name,' . $plan_id;
                    $validation['price']        = 'required|numeric|min:0';
                    $validation['duration']     = 'required|nullable';
                    $validation['max_users']    = 'required|numeric';
                    $validation['max_clients']  = 'required|numeric';
                    $validation['max_projects'] = 'required|numeric';

                    $request->validate($validation);

                    $post = $request->all();

                    if($plan->update($post))
                    {
                        return redirect()->back()->with('success', __('SubscriptionPlan Successfully updated.'));
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('Something is wrong.'));
                    }
                }
                else
                {
                    return redirect()->back()->with('error', __('SubscriptionPlan not found.'));
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
        $plan    = SubscriptionPlan::find($planID);

        if($plan)
        {
            if($plan->price <= 0)
            {
                \Auth::user()->assignPlan($plan->id);

                return redirect()->route('plans.index')->with('success', __('SubscriptionPlan Successfully activated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('SubscriptionPlan not found.'));
        }
    }
}
