<?php

namespace Laravel\Cashier;

use Exception;
use Braintree\Subscription as BraintreeSubscription;
use Braintree\Plan;
use Braintree\Plan as PaymentPlan;

class PaymentService
{
    /**
     * Get the Braintree plan that has the given ID.
     *
     * @param  string  $id
     * @return \Braintree\Plan
     * @throws \Exception
     */
    public static function findPlan($id): Plan
    {
        $plans = PaymentPlan::all();

        foreach ($plans as $plan) {
            if ($plan->id === $id) {
                return $plan;
            }
        }

        throw new Exception("Unable to find Payment plan with ID [{$id}].");
    }

    /**
     * Get the subscription as a Braintree subscription object.
     *
     * @return \Braintree\Subscription
     */
    public function findSubscription($id): BraintreeSubscription
    {
        return BraintreeSubscription::find($id);
    }


    public static function updateSubscription(string $id, $data)
    {
        $response = BraintreeSubscription::update($id, $data);

        return $response;
    }

    public static function cancelSubscription(string $id)
    {
        BraintreeSubscription::cancel($id);
    }
}
