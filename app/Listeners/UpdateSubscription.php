<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ProtoneMedia\LaravelPaddle\Events\SubscriptionUpdated;
use App\Subscription;

class UpdateSubscription
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
     * @param  SubscriptionUpdated  $event
     * @return void
     */
    public function handle(SubscriptionUpdated $event)
    {
        // $subscription = Subscription::where('paddle_subscription', $event->subscription_id)
        //                                 ->orderBy('created_at', 'desc')
        //                                 ->first();
    }
}
