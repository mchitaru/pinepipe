<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create client');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users',
            'phone'=>'nullable|string',
            'address'=>'nullable|string',
            'website'=>'nullable|string',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('clients.create');
    }
}
