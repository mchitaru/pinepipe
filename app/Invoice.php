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

    public static $SEED = 50;

    public static $status = [
        'pending',
        'outstanding',
        'partial payment',
        'paid',
        'cancelled',
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
        return $this->hasMany('App\InvoiceProduct', 'invoice_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\InvoicePayment', 'invoice_id', 'id');
    }

    public function getSubTotal()
    {
        $subTotal = 0;
        foreach($this->items as $product)
        {
            $subTotal += $product->price;
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

    public function getDue()
    {
        $due = 0;
        foreach($this->payments as $payment)
        {
            $due += $payment->amount;
        }

        return $this->getTotal() - $due;
    }

    public static function change_status($invoice_id, $status)
    {

        $invoice         = Invoice::find($invoice_id);
        $invoice->status = $status;
        $invoice->update();
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
        InvoiceProduct::where('invoice_id', '=', $this->id)->delete();

        $this->activities()->delete();
    }


}
