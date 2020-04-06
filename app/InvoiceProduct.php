<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class InvoiceProduct extends Model
{
    use NullableFields;

    protected $fillable = [
        'invoice_id', 
        'item',
        'price'
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

    public static function createProduct($post, Invoice $invoice)
    {
        if($post['type'] == 'timesheet')
        {
            $timesheet = Timesheet::find($post['timesheet_id']);    

            $timesheet->products()->create(
                [
                    'invoice_id' => $invoice->id,
                    'item' => (!empty($timesheet->task)?$timesheet->task->title:__('Project timesheet: ').\Auth::user()->dateFormat($timesheet->date)),
                    'price' => $post['price']
                ]
            );    
        }
        else if($post['type'] == 'task')
        {
            $task      = Task::find($post['task_id']);

            $task->products()->create(
                [
                    'invoice_id' => $invoice->id,
                    'item' => $task->title,
                    'price' => $post['price']
                ]
            );    
        }
        else
        {
            InvoiceProduct::create(
                [
                    'invoice_id' => $invoice->id,
                    'item' => $post['title'],
                    'price' => $post['price']
                ]
            );    
        }

        if($invoice->getTotal() == 0.0)
        {
            Invoice::change_status($invoice->id, 1);

        }else if($invoice->getTotal() > 0.0 || $invoice->getDue() < 0.0)
        {
            Invoice::change_status($invoice->id, 2);
        }
    }

    public function detachProduct(Invoice $invoice)
    {
        if($invoice->getDue() <= 0.0)
        {
            Invoice::change_status($invoice->id, 3);
        }
    }
}
