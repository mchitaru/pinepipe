<?php

namespace App\Traits;

use App\InvoiceItem;

trait Invoiceable
{
    public function items()
    {
        return $this->morphMany(InvoiceItem::class, 'invoiceable')->orderByDesc('id');
    }
}
