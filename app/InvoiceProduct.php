<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    protected $fillable = [
        'invoice_id', 
        'item',
        'price',
        'type'
    ];

    public function invoice()
    {
        return $this->hasOne('App\Invoice', 'id', 'invoice_id');
    }
}
