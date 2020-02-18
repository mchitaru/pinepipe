<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemSettingsCompanyRequest extends FormRequest
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
            'company_name' => 'required|string|max:50',
            'company_address' => 'string',
            'company_city' => 'string',
            'company_state' => 'string',
            'company_zipcode' => 'string',
            'company_country' => 'string',
            'company_phone' => 'string',
            'company_email' => 'required',
            'company_email_from_name' => 'required|string',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('profile.show').'/#company';
    }
}
