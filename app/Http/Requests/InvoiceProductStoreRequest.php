<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Invoice;

class InvoiceProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invoice = $this->route()->parameter('invoice');

        if($invoice->created_by != \Auth::user()->creatorId())
            return false;            

        return $this->user()->can('create invoice product');
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
            ];
        }else if($this->type == 'task'){
            return [
                'task_id' => 'required',
                'price' => 'required',
            ];
        }else{
            return [
                'title' => 'required',
                'price' => 'required',
            ];
        }
    }

    protected function getRedirectUrl()
    {
        $this->session()->flash('type', $this->type);

        $invoice = $this->route()->parameter('invoice');

        return route('invoices.products.create', $invoice);
    }
}
