<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectTaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->can('create task'))
        {
            $project = $this->route()->parameter('project');

            return $project->created_by == \Auth::user()->creatorId();
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
            'title' => 'required|string|min:2|max:60',
            'description' => 'nullable|string',
            'priority' => 'required|string',
            'user_id' => 'nullable|array',
            'start_date' => 'required|date',
            'due_date' => 'required|date',
        ];
    }

    protected function getRedirectUrl()
    {
        $project = $this->route()->parameter('project');

        return route('projects.task.create', $project->id);
    }
}
