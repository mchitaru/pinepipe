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

    protected $nullable = [
        'notes'
    ];

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

        static::deleting(function ($payment) {

        });
    }

    public function invoice()
    {
        return $this->hasOne('App\Invoice','id','invoice_id');
    }

    public static function createPayment($post, Invoice $invoice)
    {
        if(isset($post['category_id']) && !is_numeric($post['category_id'])) {

            //new category
            $category = Category::create(['name' => $post['category_id'],
                                            'class' => Payment::class]);
            $post['category_id'] = $category->id;
        }

        $latest_payment = Payment::select('payments.*')->join('invoices', 'payments.invoice_id', '=', 'invoices.id')->where('invoices.created_by', '=', \Auth::user()->creatorId())->latest()->first();

        $payment = Payment::create(
            [
                'transaction_id' => $latest_payment?($latest_payment->transaction_id + 1):1,
                'invoice_id' => $invoice->id,
                'category_id' => $post['category_id'],
                'amount' => $post['amount'],
                'date' => $post['date'],
                'notes' => $post['notes'],
            ]
        );

        $invoice->updateStatus();
    }

    public function updatePayment($post)
    {
        if(isset($post['category_id']) && !is_numeric($post['category_id'])) {

            //new category
            $category = Category::create(['name' => $post['category_id'],
                                            'class' => Payment::class]);
            $post['category_id'] = $category->id;
        }

        $this->update($post);
    }
}
