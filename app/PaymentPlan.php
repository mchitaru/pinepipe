<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration',
        'max_users',
        'max_clients',
        'max_projects',
        'description',
    ];

    public static $arrDuration = [
        'unlimited' => 'Unlimited',
        'month' => 'Monthly',
        'year' => 'Yearly',
    ];

    public static function total_plan()
    {
        return PaymentPlan::count();
    }

    public static function most_purchese_plan()
    {
        $free_plan=  PaymentPlan::where('price','<=',0)->first()->id;
        return User:: select(DB::raw('count(*) as total'))->where('type','=','company')->where('plan_id','!=',$free_plan)->groupBy('plan_id')->first();
    }
}
