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
            $tab = $this->route()->parameter('tab');

            if($tab == 'personal'){
                return [
                    'name' => 'required|max:120',
                    'email' => 'required|email|unique:users,email,' .  \Auth::user()->id,
                    'avatar' => 'mimetypes:image/*|max:2048',
                    'bio' => 'string|nullable',
                ];
            }else{
                return [
                    'notify_task_assign' => 'boolean',
                    'notify_project_assign' => 'boolean',
                    'notify_project_activity' => 'boolean',
                    'notify_item_overdue' => 'boolean',
                    'notify_newsletter' => 'boolean',
                    'notify_major_updates' => 'boolean',
                    'notify_minor_updates' => 'boolean',
                ];
            }

        }else{

            return [
                'current_password' => 'required',
                'new_password' => 'required|confirmed|min:6',
            ];
        }
    }

    protected function getRedirectUrl()
    {
        return route('profile.show');
    }
}
