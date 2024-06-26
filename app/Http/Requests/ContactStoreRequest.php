<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', 'App\Contact');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|min:3',
            'client_id'=>'nullable',
            'email'=>'nullable|email',
            'phone'=>'nullable|string',
            'tags'=>'nullable|array'
        ];
    }

    protected function getRedirectUrl()
    {
        return route('contacts.create');
    }
}
