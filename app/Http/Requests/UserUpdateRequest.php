<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->isMethod('put'))
        {
            return $this->user()->can('edit user');
        }

        return $this->user()->can('delete user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->isMethod('put'))
        {
            $user_id = $this->route()->parameter('user');

            if(\Auth::user()->type == 'super admin')
            {
                return [
                    'name' => 'required|max:120',
                    'email' => 'required|email|unique:users,email,' . $user_id,
                ];
            }else{

                return [
                    'name' => 'required|max:120',
                    'email' => 'required|email|unique:users,email,' . $user_id,
                    'role' => 'required|string',
                    'client_id' => 'nullable|numeric'
                ];                        
            }
        }else {
            return [
            ];                        

        }
    }

    protected function getRedirectUrl()
    {
        $user = $this->route()->parameter('user');
        return route('users.edit', $user);
    }
}
