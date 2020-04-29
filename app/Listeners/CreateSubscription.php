<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\SubscriptionPlan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ProtoneMedia\LaravelPaddle\Events\SubscriptionCreated;
use App\Subscription;

class CreateSubscription
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SubscriptionCreated  $event
     * @return void
     */
    public function handle(SubscriptionCreated $event)
    {
        $plan_id = $event->passthrough['plan_id'];
        $plan = SubscriptionPlan::find($plan_id);

        $subscription = Subscription::create(['user_id' => $event->passthrough['user_id'],
                                                'paddle_subscription' => $event->subscription_id,
                                                'paddle_plan' => $event->subscription_plan_id,
                                                'trial_ends_at' => Carbon::now()->addDays(14),
                                                'ends_at' => null,
                                                'max_clients' => $plan->max_clients,
                                                'max_projects' => $plan->max_projects,
                                                'max_users' => $plan->max_users,
                                                'max_space' => $plan->max_space]);

    }
}
