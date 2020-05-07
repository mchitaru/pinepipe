<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceItemUpdateRequest extends FormRequest
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
            'price' => 'required',
            'text' => 'required|string'
        ];
    }

    protected function getRedirectUrl()
    {
        $invoice = $this->route()->parameter('invoice');
        $item = $this->route()->parameter('item');

        return route('invoices.items.edit', $invoice, $item);
    }
}
