<?php

namespace App\Traits;

use App\InvoiceProduct;

trait Invoiceable
{
    public function products()
    {
        return $this->morphMany(InvoiceProduct::class, 'invoiceable')->orderByDesc('id');
    }
}
