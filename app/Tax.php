<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'name', 
        'rate', 
    ];

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($tax) {
            if ($user = \Auth::user()) {
                $tax->user_id = $user->id;
                $tax->created_by = $user->created_by;
            }
        });

        static::deleting(function ($article) {

        });
    }
}
