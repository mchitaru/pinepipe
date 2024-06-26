<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Invoice;

class InvoiceItemStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->invoice);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->type == 'timesheet'){
            return [
                'timesheet_id' => 'required',
                'price' => 'required',
                'quantity' => 'required|numeric',
                'text' => 'required|string'
            ];
        }else if($this->type == 'task'){
            return [
                'task_id' => 'required',
                'price' => 'required',
                'quantity' => 'required|numeric',
                'text' => 'required|string'
            ];
        }else if($this->type == 'expense'){
            return [
                'expense_id' => 'required',
                'price' => 'required',
                'quantity' => 'required|numeric',
                'text' => 'required|string'
            ];
        }else{
            return [
                'price' => 'required',
                'quantity' => 'required|numeric',
                'text' => 'required|string'
            ];
        }
    }

    protected function getRedirectUrl()
    {
        $this->session()->flash('type', $this->type);

        $invoice = $this->route()->parameter('invoice');

        return route('invoices.items.create', $invoice);
    }
}
