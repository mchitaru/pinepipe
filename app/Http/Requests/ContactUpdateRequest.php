<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->contact);
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
            'client_id'=>'nullable|integer',
            'email'=>'nullable|email',
            'phone'=>'nullable|string',
            'address'=>'nullable|string',
            'company'=>'nullable|string',
            'job'=>'nullable|string',
            'website'=>'nullable|string',
            'birthday'=>'nullable|date',
            'notes'=>'nullable|string',
            'tags'=>'nullable|array'
        ];
    }

    protected function getRedirectUrl()
    {
        $contact = $this->route()->parameter('contact');
        return route('contacts.edit', $contact);
    }
}
