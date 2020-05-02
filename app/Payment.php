<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Categorizable;

class Payment extends Model
{
    use Categorizable;

    protected $fillable = [
        'transaction_id',
        'invoice_id',
        'amount',
        'date',
        'notes',
        'category_id'
    ];

    public function invoice()
    {
        return $this->hasOne('App\Invoice','id','invoice_id');
    }

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if ($user = \Auth::user()) {
                $payment->user_id = $user->id;
                $payment->created_by = $user->creatorId();
            }
        });
    }
}
