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
                'name' => 'required_without:notify_item_overdue|max:120',
                'handle' => 'required_without:notify_item_overdue|unique:users,handle,' .  \Auth::user()->id,
                'email' => 'required_without:notify_item_overdue|email|unique:users,email,' .  \Auth::user()->id,
                'avatar' => 'mimetypes:image/*|max:2048',
                'bio' => 'string|nullable',
                'locale' => 'required_without:notify_item_overdue|string',

                'notify_task_assign' => 'boolean',
                'notify_project_assign' => 'boolean',
                'notify_project_activity' => 'boolean',
                'notify_item_overdue' => 'boolean',
                'notify_newsletter' => 'boolean',
                'notify_major_updates' => 'boolean',
                'notify_minor_updates' => 'boolean',
                ];
        }else{

            return [
                'current_password' => 'nullable',
                'new_password' => 'required|confirmed|min:6',
            ];
        }
    }

    protected function getRedirectUrl()
    {
        return route('profile.edit');
    }
}
