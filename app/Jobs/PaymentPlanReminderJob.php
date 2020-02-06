<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\PaymentPlan;
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
        $this->free_plan = PaymentPlan::where('price', '=', '0.0')->first();

        $this->users = User::with('plan')
        ->where('type', '=', 'company')
        ->where('plan_id', '!=', $this->free_plan->id)
        ->where('plan_expire_date', '<', date('Y-m-d'))
        ->get();
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
            $user->assignPlan($this->free_plan->id);
            $user->notify(new PaymentPlanExpiredAlert($user));
        }        
    }
}
