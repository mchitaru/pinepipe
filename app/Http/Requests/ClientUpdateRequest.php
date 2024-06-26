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
        return $this->user()->can('update', $this->client);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('put'))
        {
            $client = $this->route()->parameter('client');

            return [
                'name'=>'required|max:120',
                'email'=>'nullable|email',
                'phone'=>'nullable|string',
                'address'=>'nullable|string',
                'website'=>'nullable|string',
                'tax'=>'nullable|string',
                'registration'=>'nullable|string',
                'avatar' => 'mimetypes:image/*|max:2048'
            ];

        }else{

            return [
                'archived' => 'nullable|boolean'
            ];
        }
    }

    protected function getRedirectUrl()
    {
        if ($this->isMethod('put'))
        {
            $client = $this->route()->parameter('client');
            return route('clients.edit', $client);

        }else{

            return URL::previous();
        }
    }
}
