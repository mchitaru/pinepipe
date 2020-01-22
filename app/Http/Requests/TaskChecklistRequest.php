<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskChecklistRequest extends FormRequest
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
            'name' => 'nullable',
            'status' => 'nullable'
        ];
    }
    
    protected function getRedirectUrl()
    {
        $task = $this->route()->parameter('task');
        return route('tasks.checklist.index', $task->id);
    }
}
