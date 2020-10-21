<?php

namespace App;

use Exception;
use Carbon\Carbon;
use App\SubscriptionPlan;
use LogicException;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->owner();
    }

    /**
     * Get the model related to the subscription.
     */
    public function owner()
    {
        $model = 'App\\User';

        $model = new $model;

        return $this->belongsTo(get_class($model), $model->getForeignKey());
    }

    public function plan()
    {
        return $this->belongsTo('App\SubscriptionPlan', 'paddle_plan', 'paddle_id');
    }
    
    /**
     * Determine if the subscription is active, on trial, or within its grace period.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->active() || $this->onTrial() || $this->onGracePeriod();
    }

    /**
     * Determine if the subscription is active.
     *
     * @return bool
     */
    public function active()
    {
        return is_null($this->ends_at) || $this->onGracePeriod();
    }

    /**
     * Determine if the subscription is no longer active.
     *
     * @return bool
     */
    public function cancelled()
    {
        return ! is_null($this->ends_at);
    }

    /**
     * Determine if the subscription is within its trial period.
     *
     * @return bool
     */
    public function onTrial()
    {
        if (! is_null($this->trial_ends_at)) {
            return Carbon::today()->lt($this->trial_ends_at);
        }

        return false;
    }

    /**
     * Determine if the subscription is within its grace period after cancellation.
     *
     * @return bool
     */
    public function onGracePeriod()
    {
        if (! is_null($endsAt = $this->ends_at)) {
            return Carbon::now()->lt($endsAt);
        }

        return false;
    }
}
