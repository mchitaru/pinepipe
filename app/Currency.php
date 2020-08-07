<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class Currency extends Model
{
    use NullableFields;

    protected $fillable = [
        'code',
        'name',
        'rate',
    ];

    protected $nullable = [
        'name',
    ];

    public static function updateRates($rates) 
    {
        foreach ($rates->rates as $key => $rate) {
            
            Currency::updateOrCreate(['code' => $key], ['code' => $key, 'rate' => $rate]);
        }
    }
 }
