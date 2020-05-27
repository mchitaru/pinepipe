<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUnsubscribeRequest extends FormRequest
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
            'notify_task_assign' => 'boolean',
            'notify_project_assign' => 'boolean',
            'notify_project_activity' => 'boolean',
            'notify_item_overdue' => 'boolean',
            'notify_newsletter' => 'boolean',
            'notify_major_updates' => 'boolean',
            'notify_minor_updates' => 'boolean',
            ];
    }

    protected function getRedirectUrl()
    {
        $user = $this->route()->parameter('user');

        return route('unsubscribe.edit', $user);
    }
}
