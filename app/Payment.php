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
        'notes'
    ];

    public function invoice()
    {
        return $this->hasOne('App\Invoice','id','invoice_id');
    }
}
