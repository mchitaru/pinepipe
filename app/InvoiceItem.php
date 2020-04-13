<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class InvoiceItem extends Model
{
    use NullableFields;

    protected $fillable = [
        'invoice_id', 
        'name',
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

    public static function createItem($post, Invoice $invoice)
    {
        if($post['type'] == 'timesheet')
        {
            $timesheet = Timesheet::find($post['timesheet_id']);    

            $timesheet->invoiceables()->create(
                [
                    'invoice_id' => $invoice->id,
                    'name' => (!empty($timesheet->task)?$timesheet->task->title:__('Project timesheet: ').\Auth::user()->dateFormat($timesheet->date)),
                    'price' => $post['price']
                ]
            );    
        }
        else if($post['type'] == 'task')
        {
            $task      = Task::find($post['task_id']);

            $task->invoiceables()->create(
                [
                    'invoice_id' => $invoice->id,
                    'name' => $task->title,
                    'price' => $post['price']
                ]
            );    
        }
        else
        {
            InvoiceItem::create(
                [
                    'invoice_id' => $invoice->id,
                    'name' => $post['name'],
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

    public function detachItem(Invoice $invoice)
    {
        if($invoice->getDue() <= 0.0)
        {
            Invoice::change_status($invoice->id, 3);
        }
    }
}
