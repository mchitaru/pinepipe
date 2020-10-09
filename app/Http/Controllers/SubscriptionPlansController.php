<?php

namespace App\Http\Controllers;

use App\SubscriptionPlan;
use App\Utility;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;

class SubscriptionPlansController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', 'App\SubscriptionPlan');

        $plans = SubscriptionPlan::get();
        return view('plan.index', compact('plans'));
    }


    public function create()
    {
        Gate::authorize('create', 'App\SubscriptionPlan');

        return view('plan.create');
    }


    public function store(Request $request)
    {
        Gate::authorize('create', 'App\SubscriptionPlan');

        $validation                 = [];
        $validation['name']         = 'required|unique:subscription_plans';
        $validation['paddle_id']    = 'required|string';
        $validation['price']        = 'required|numeric|min:0';
        $validation['deal']         = 'required|boolean';
        $validation['duration']     = 'nullable|numeric';
        $validation['max_users']    = 'nullable|numeric';
        $validation['max_clients']  = 'nullable|numeric';
        $validation['max_projects'] = 'nullable|numeric';
        $validation['max_space']    = 'nullable|numeric';
        $validation['description']  = 'nullable|string';
        $request->validate($validation);
        $post = $request->all();

        SubscriptionPlan::create($post);

        return redirect()->back()->with('success', __('Subscription Plan successfully created.'));
    }


    public function edit(SubscriptionPlan $plan)
    {
        Gate::authorize('update', $plan);

        return view('plan.edit', compact('plan'));
    }


    public function update(Request $request, SubscriptionPlan $plan)
    {
        Gate::authorize('update', $plan);

        if(!empty($plan))
        {
            $validation                 = [];
            $validation['name']         = 'required|unique:subscription_plans,name,' . $plan_id;
            $validation['paddle_id']    = 'required|string';
            $validation['price']        = 'required|numeric|min:0';
            $validation['deal']         = 'required|boolean';
            $validation['duration']     = 'nullable|numeric';
            $validation['max_users']    = 'nullable|numeric';
            $validation['max_clients']  = 'nullable|numeric';
            $validation['max_projects'] = 'nullable|numeric';
            $validation['max_space']    = 'nullable|numeric';
            $validation['description']  = 'nullable|string';

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

    public function destroy(SubscriptionPlan $plan)
    {
        Gate::authorize('delete', $plan);

        return redirect()->back();
    }
}
