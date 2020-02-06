<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isMethod('patch'))
            return \Auth::check() && \Auth::user()->can('change password account');

        return \Auth::check();
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
            return [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' .  \Auth::user()->id
            ];
        }else{

            return [
                'current_password' => 'required',
                'new_password' => 'required|confirmed|min:6',
            ];
        }
    }

    protected function getRedirectUrl()
    {
        if ($this->isMethod('put')){

            return route('profile.show').'/#profile';
        }else{

            return route('profile.show').'/#password';
        }
    }
}
