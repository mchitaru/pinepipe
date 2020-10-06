<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->route()->parameter('user');

        if($user == null ||
            $user->type == 'super admin' ||
            $user->type == 'company'){

            return false;
        }

        if($this->isMethod('put'))
        {
            return ($user->created_by == $this->user()->id) &&
                    $this->user()->can('edit user');
        }

        return ($user->created_by == $this->user()->id) &&
                $this->user()->can('delete user');
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
            $user = $this->route()->parameter('user');

            if($this->user()->type == 'super admin')
            {
                return [
                    'name' => 'required|max:120',
                    'email' => 'required|email|unique:users,email,' . $user->id,
                ];
            }else{

                return [
                    'name' => 'required|max:120',
                    'email' => 'required|email|unique:users,email,' . $user->id,
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
