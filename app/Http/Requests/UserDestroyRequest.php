<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\URL;
use App\User;

class UserDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user;

        if($user && !$user->isSuperAdmin()){

            return $this->user()->can('delete', $user);
        }

        return false;        
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    protected function getRedirectUrl()
    {
        return URL::previous();
    }
}
