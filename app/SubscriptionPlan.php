<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
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

    public static function total_plan()
    {
        return SubscriptionPlan::count();
    }
}
