<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->can('update', $this->invoice);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'nullable|integer',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'discount' => 'required|numeric|min:0',
            'currency' => 'required|string',
            'rate' => 'required_unless:currency,'.\Auth::user()->getCurrency().'|numeric',
            'locale' => 'required|string',
            'tax_id' => 'nullable|integer',
            'increment' => 'integer'
        ];
    }

    protected function getRedirectUrl()
    {
        $invoice = $this->route()->parameter('invoice');

        return route('invoices.edit', $invoice);
    }
}
