<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoicePaymentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invoice = $this->route()->parameter('invoice');

        if($invoice->created_by != \Auth::user()->created_by)
            return false;

        return $this->user()->can('edit invoice');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'category_id' => 'nullable'
        ];
    }

    protected function getRedirectUrl()
    {
        $invoice = $this->route()->parameter('invoice');

        return route('invoices.payments.create', $invoice);
    }
}
