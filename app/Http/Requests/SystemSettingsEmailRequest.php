<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemSettingsEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->can('manage system settings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mail_driver' => 'required|string|max:50',
            'mail_host' => 'required|string|max:50',
            'mail_port' => 'required|string|max:50',
            'mail_username' => 'required|string|max:50',
            'mail_password' => 'required|string|max:50',
            'mail_encryption' => 'required|string|max:50',
            'mail_from_address' => 'required|string|max:50',
            'mail_from_name' => 'required|string|max:50',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('profile.show').'/#email';
    }
}
