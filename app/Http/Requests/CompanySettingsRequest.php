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
        return \Auth::user()->can('manage company settings');
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
            'email' => 'nullable|email|unique:company_settings,email,'.\Auth::user()->creatorId().',created_by',
            'address' => 'string',
            'city' => 'string',
            'state' => 'string',
            'zipcode' => 'string',
            'country' => 'string',
            'phone' => 'string',
            'invoice' => 'string',
            'currency' => 'required',
            'file' => 'nullable|mimes:jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,zip,rar|max:2048'
        ];
    }

    protected function getRedirectUrl()
    {
        return route('profile.show');
    }
}
