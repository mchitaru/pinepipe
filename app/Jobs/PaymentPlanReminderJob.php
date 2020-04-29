<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\SubscriptionPlan;
use App\User;
use App\Notifications\PaymentPlanExpiredAlert;

class PaymentPlanReminderJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;
    protected $free_plan;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->free_plan = SubscriptionPlan::where('price', '=', '0.0')->first();

        //TO DO
        // $this->users = User::with('plan')
        //     ->where('type', '=', 'company')
        //     ->where('plan_id', '!=', $this->free_plan->id)
        //     ->where('subscription_ends_at', '<', time())
        //     ->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach($this->users as $user)
        {
            $user->notify(new PaymentPlanExpiredAlert($user));
        }
    }
}
