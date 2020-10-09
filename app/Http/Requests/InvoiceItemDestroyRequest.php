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
        return $this->user()->can('delete', $this->invoice);
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
