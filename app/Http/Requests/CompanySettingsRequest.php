<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanySettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->companySettings){

            return $this->user()->can('update', $this->companySettings);
        }

        return $this->user()->can('create', 'App\CompanySettings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zipcode' => 'nullable|string',
            'country' => 'nullable|string',
            'phone' => 'nullable|string',
            'website' => 'nullable|string',
            'tax' => 'nullable|string',
            'tax_payer' => 'required|boolean',
            'registration' => 'string|nullable',
            'bank' => 'string|nullable',
            'iban' => 'string|nullable',
            'invoice' => 'nullable|string',
            'receipt' => 'nullable|string',
            'currency' => 'required',
            'logo' => 'nullable|mimetypes:image/*|max:2048'
        ];
    }

    protected function getRedirectUrl()
    {
        return route('profile.edit');
    }
}
