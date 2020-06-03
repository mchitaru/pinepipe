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
            $validation                 = [];
            $validation['name']         = 'required|unique:plans';
            $validation['price']        = 'required|numeric|min:0';
            $validation['duration']     = 'required|nullable';
            $validation['max_users']    = 'required|numeric';
            $validation['max_clients']  = 'required|numeric';
            $validation['max_projects'] = 'required|numeric';
            $request->validate($validation);
            $post = $request->all();

            SubscriptionPlan::create($post);

            return redirect()->back()->with('success', __('Subscription Plan successfully created.'));
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

                $plan->update($post);
                
                return redirect()->back()->with('success', __('Subscription Plan successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Subscription Plan not found.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }
}
