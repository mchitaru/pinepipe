<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Taggable;

use App\CompanySettings;

use App\Currency as CurrencyRate;

class Invoice extends Model
{
    use NullableFields, Taggable;

    protected $fillable = [
        'number',
        'increment',
        'project_id',
        'status',
        'issue_date',
        'due_date',
        'discount',
        'tax_id',
        'user_id',
        'currency',
        'rate',
        'locale',
        'created_by'
    ];

    protected $nullable = [
        'tax_id',
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
    
    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if ($user = \Auth::user()) {
                $invoice->user_id = $user->id;
                $invoice->created_by = $user->creatorId();
            }
        });

        static::deleting(function ($invoice) {

            $invoice->payments()->each(function($payment) {
                $payment->delete();
            });

            $invoice->items()->each(function($item) {
                $item->delete();
            });

            $invoice->tags()->detach();
        });
    }

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
        return $this->hasMany('App\Payment', 'invoice_id', 'id');
    }

    public function getSubTotal()
    {
        $subTotal = 0;
        foreach($this->items as $item)
        {
            $subTotal += $item->quantity * $item->price;
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
        return '<span class="badge '.Invoice::$badge[$this->status].'">'.__(Invoice::translateStatus($this->status)).'</span>';
    }

    public function getCurrency()
    {
        return $this->currency?$this->currency:(\Auth::user()->companySettings ? \Auth::user()->companySettings->currency : \Auth::user()->getDefaultCurrency());
    }

    public function getLocale()
    {
        return $this->locale?$this->locale:\Auth::user()->locale;
    }

    static function translateStatus($status)
    {
        switch($status)
        {
            case 1: return __('outstanding');
            case 2: return __('partial payment');
            case 3: return __('paid');
            case 4: return __('cancelled');    
            default: return __('pending');
        }
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
        $invoice->increment = $last_invoice ? ($last_invoice->increment + 1) : 1;
        $invoice->number = \Auth::user()->invoiceNumberFormat($invoice->increment);
        $invoice->save();

        Activity::createInvoice($invoice);

        return $invoice;
    }

    public function updateInvoice($post)
    {
        $this->update($post);

        Activity::updateInvoice($this);
    }

    public function priceFormat($price)
    {        
        return \Helpers::priceFormat($price, $this->getCurrency());

    }

    public function priceConvert($price)
    {
        return \Helpers::priceConvert($price, $this->rate);
    }
}
