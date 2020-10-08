<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', 'App\User');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {        
        if(\Auth::user()->type == 'super admin') {

            return [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ];

        }else{

            return [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'role' => 'required|string',
                'client_id' => 'required_if:role,client'
            ];

        }
    }

    protected function getRedirectUrl()
    {
        return route('users.create');
    }
}
