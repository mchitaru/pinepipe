<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create task');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(\Auth::user()->type == 'company') {
            return [
                'title' => 'required|string|min:2|max:60',
                'description' => 'nullable|string',
                'priority' => 'required|string',
                'user_id' => 'nullable|array', //+
                'project_id' => 'nullable|integer',
                'start_date' => 'required|date',
                'due_date' => 'required|date',
            ];
        }else{
            return [
                'title' => 'required|string|min:2|max:20',
                'description' => 'nullable|string',
                'priority' => 'required|string',
                'project_id' => 'nullable|integer',
                'start_date' => 'required|date',
                'due_date' => 'required|date',
            ];
        }
    }

    protected function getRedirectUrl()
    {
        $project_id = $this->route()->parameter('project');
        return route('projects.task.create', $project_id);
    }
}
