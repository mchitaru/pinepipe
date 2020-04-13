<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Invoice;
use Illuminate\Support\Facades\URL;

class InvoiceItemDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $invoice = $this->route()->parameter('invoice');

        if(!$invoice->created_by == \Auth::user()->creatorId())
            return false;            

        return $this->user()->can('delete invoice item');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    protected function getRedirectUrl()
    {
        return URL::previous();
    }
}
