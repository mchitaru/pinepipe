<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'paddle_id',
        'price',
        'deal',
        'duration', 
        'max_users',
        'max_clients',
        'max_projects',
        'max_space',
        'description',
    ];

    protected $nullable = [
        'duration',
        'max_users',
        'max_clients',
        'max_projects',
        'max_space',
        'description',
    ];

    public static function total_plan()
    {
        return SubscriptionPlan::count();
    }
}
