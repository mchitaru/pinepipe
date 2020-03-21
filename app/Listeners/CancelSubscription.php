<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use ProtoneMedia\LaravelPaddle\Events\SubscriptionCancelled;
use App\Subscription;

class CancelSubscription
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
     * @param  SubscriptionCancelled  $event
     * @return void
     */
    public function handle(SubscriptionCancelled $event)
    {
        // dump($event);

        $subscription = Subscription::where('paddle_subscription', $event->subscription_id)
                                        ->orderBy('created_at', 'desc')
                                        ->first();

        $subscription->ends_at = Carbon::now();
        $subscription->save();
    }
}
