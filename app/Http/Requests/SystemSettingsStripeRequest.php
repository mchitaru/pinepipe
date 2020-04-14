<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemSettingsStripeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->can('manage stripe settings');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'stripe_key' => 'required|string|max:50',
            'stripe_secret' => 'required|string|max:50',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('profile.show');
    }

}
