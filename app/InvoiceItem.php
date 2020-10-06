<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

use App\Scopes\CompanyTenantScope;

class InvoiceItem extends Model
{
    use NullableFields;

    protected $fillable = [
        'invoice_id',
        'text',
        'price',
        'quantity'
    ];

    protected $nullable = [
        'invoiceable_type',
        'invoiceable_id',
	];

    public function invoice()
    {
        return $this->hasOne('App\Invoice', 'id', 'invoice_id');
    }

    public function invoiceable()
    {
        return $this->morphTo();
    }

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyTenantScope);

        static::creating(function ($item) {
            if ($user = \Auth::user()) {
                $item->created_by = $user->created_by;
            }
        });

        static::deleting(function ($item) {

        });
    }

    public static function createItem($post, Invoice $invoice)
    {
        if($post['type'] == 'timesheet')
        {
            $timesheet = Timesheet::find($post['timesheet_id']);

            $timesheet->invoiceables()->create(
                [
                    'invoice_id' => $invoice->id,
                    'text' => $post['text'],
                    'price' => $post['price'],
                    'quantity' => $post['quantity']
                ]
            );
        }
        else if($post['type'] == 'task')
        {
            $task      = Task::find($post['task_id']);

            $task->invoiceables()->create(
                [
                    'invoice_id' => $invoice->id,
                    'text' => $post['text'],
                    'price' => $post['price'],
                    'quantity' => $post['quantity']
                ]
            );
        }
        else if($post['type'] == 'expense')
        {
            $expense      = Expense::find($post['expense_id']);

            $expense->invoiceables()->create(
                [
                    'invoice_id' => $invoice->id,
                    'text' => $post['text'],
                    'price' => $post['price'],
                    'quantity' => $post['quantity']
                ]
            );
        }
        else
        {
            InvoiceItem::create(
                [
                    'invoice_id' => $invoice->id,
                    'text' => $post['text'],
                    'price' => $post['price'],
                    'quantity' => $post['quantity']
                ]
            );
        }

        $invoice->updateStatus();
    }

}
