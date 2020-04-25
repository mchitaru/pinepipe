<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Project;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $project = Project::find($this->project_id);

        if($project && !$project->enabled)
            return false;

        return $this->user()->can('create task');
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
            'priority' => 'required|numeric',
            'users' => (\Auth::user()->type == 'company') ? 'nullable|array' : 'required|array', //+
            'project_id' => 'nullable|integer',
            'due_date' => 'nullable|date',
            'tags'=>'nullable|array'
        ];
    }

    protected function getRedirectUrl()
    {
        return route('tasks.create');
    }
}
