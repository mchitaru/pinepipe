<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('edit client');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $client = $this->route()->parameter('client');

        return [
            'name'=>'required|max:120',
            'email'=>'nullable|email',
            'phone'=>'nullable|string',
            'address'=>'nullable|string',
            'website'=>'nullable|string',
            'avatar' => 'mimetypes:image/*|max:2048'
        ];
    }

    protected function getRedirectUrl()
    {
        $client = $this->route()->parameter('client');
        return route('clients.edit', $client);
    }
}
