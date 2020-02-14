<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    protected $fillable = [
        'transaction_id',
        'invoice_id', 
        'amount',
        'date',
        'payment_id',
        'notes'
    ];

    public function paymentType()
    {
        return $this->hasOne('App\PaymentType','id','payment_id');
    }
    public function invoice()
    {
        return $this->hasOne('App\Invoice','id','invoice_id');
    }
}
