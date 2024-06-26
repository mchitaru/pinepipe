<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppSumoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:120',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'code' => 'required|string|min:7|max:7',
        ];
    }
}
