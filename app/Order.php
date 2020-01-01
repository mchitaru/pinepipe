<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id','name','email','card_number','card_exp_month','card_exp_year','plan_name','plan_id','price','price_currency','txn_id','payment_status','receipt','user_id'
    ];

    public static function total_orders(){
        return Order::count();
    }
    public static function total_orders_price(){
        return Order::sum('price');
    }
}
