<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->can('create', ['App\Invoice', $this->project]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_id' => 'required|integer',
            'project_id' => 'nullable|integer',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'currency' => 'required|string',
            'rate' => 'required_unless:currency,'.\Auth::user()->getCurrency().'|numeric',
            'locale' => 'required|string',
            'tax_id' => 'nullable|integer',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('invoices.create');
    }
}
