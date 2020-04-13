<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Actionable;
use Iatstuti\Database\Support\NullableFields;

class Invoice extends Model
{
    use NullableFields;
    use Actionable;

    protected $fillable = [
        'invoice_id',
        'project_id',
        'status',
        'issue_date',
        'due_date',
        'discount',
        'tax_id',
        'notes',
        'created_by',
    ];

    protected $nullable = [
        'tax_id',
        'notes'
    ];

    public static $SEED = 10;

    public static $status = [
        'pending',
        'outstanding',
        'partial payment',
        'paid',
        'cancelled',
    ];

    public static $badge = [
        'badge-info',
        'badge-danger',
        'badge-warning',
        'badge-success',
        'badge-light',
    ];

    public function project()
    {
        return $this->hasOne('App\Project', 'id', 'project_id');
    }

    public function tax()
    {
        return $this->hasOne('App\Tax', 'id', 'tax_id');
    }

    public function items()
    {
        return $this->hasMany('App\InvoiceItem', 'invoice_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\InvoicePayment', 'invoice_id', 'id');
    }

    public function getSubTotal()
    {
        $subTotal = 0;
        foreach($this->items as $item)
        {
            $subTotal += $item->price;
        }

        return $subTotal;
    }


    public function getTax()
    {
        $discount_factor = 1 - $this->discount/100.0;

        $tax = ($this->getSubTotal() * $discount_factor * (!empty($this->tax)?$this->tax->rate:0)) / 100.00;

        return $tax;
    }

    public function getTotal()
    {
        $discount_factor = 1 - $this->discount/100.0;

        return ($this->getSubTotal() * $discount_factor) + $this->getTax();
    }

    public function getPaid()
    {
        $paid = 0;
        foreach($this->payments as $payment)
        {
            $paid += $payment->amount;
        }

        return $paid;
    }

    public function getDue()
    {
        return $this->getTotal() - $this->getPaid();
    }

    public function getStatusBadge()
    {
        return '<span class="badge '.Invoice::$badge[$this->status].'">'.__(Invoice::$status[$this->status]).'</span>';
    }

    public function updateStatus()
    {
        if($this->status != 4)
        {
            $total = $this->getTotal();
            $paid = $this->getPaid();

            $status = 0;

            if($total && ($total == $paid)){
                $status = 3;
            }else if($total && ($paid == 0.0)) {
                $status = 1;
            }else if($paid > 0.0){
                $status = 2;
            }

            $this->status = $status;
            $this->update();
        }
    }

    public static function createInvoice($post)
    {
        $last_invoice = Invoice::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();

        $invoice              = Invoice::make($post);
        $invoice->status      = 0;
        $invoice->discount    = 0;
        $invoice->invoice_id = $last_invoice?($last_invoice->id + 1):1;
        $invoice->created_by  = \Auth::user()->creatorId();
        $invoice->save();

        Activity::createInvoice($invoice);

        return $invoice;
    }

    public function updateInvoice($post)
    {
        $this->update($post);

        Activity::updateInvoice($this);
    }

    public function detachInvoice()
    {
        InvoicePayment::where('invoice_id', '=', $this->id)->delete();
        InvoiceItem::where('invoice_id', '=', $this->id)->delete();

        $this->activities()->delete();
    }


}
