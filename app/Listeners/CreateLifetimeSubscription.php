<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ProtoneMedia\LaravelPaddle\Events\GenericWebhook;
use App\Subscription;
use App\SubscriptionPlan;
use Carbon\Carbon;

class CreateLifetimeSubscription
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
     * @param  object  $event
     * @return void
     */
    public function handle(GenericWebhook $event)
    {
        $plan_id = $event->passthrough['plan_id'];
        $plan = SubscriptionPlan::find($plan_id);

        $subscription = Subscription::create(['user_id' => $event->passthrough['user_id'],
                                                'paddle_subscription' => 'Freelancer Lifetime Deal',
                                                'paddle_plan' => $event->p_product_id,
                                                'trial_ends_at' => null,
                                                'ends_at' => null,
                                                'max_clients' => $plan->max_clients,
                                                'max_projects' => $plan->max_projects,
                                                'max_users' => $plan->max_users,
                                                'max_space' => $plan->max_space]);
    }
}
