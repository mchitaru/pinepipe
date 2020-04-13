<?php

namespace App\Traits;

use App\InvoiceItem;

trait Invoiceable
{
    public function invoiceables()
    {
        return $this->morphMany(InvoiceItem::class, 'invoiceable')->orderByDesc('id');
    }
}
