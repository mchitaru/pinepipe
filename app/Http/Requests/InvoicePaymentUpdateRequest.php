<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoicePaymentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invoice = $this->invoice;

        if($invoice->created_by != \Auth::user()->created_by)
            return false;

        return $this->user()->can('update', $invoice);
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
        $payment = $this->route()->parameter('payment');

        return route('invoices.payments.edit', $invoice, $payment);
    }
}
