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
        if(\Auth::user()->type == 'company') {
            return [
                'title' => 'required|string|min:2|max:60',
                'description' => 'nullable|string',
                'priority' => 'required|string',
                'user_id' => 'nullable|array', //+
                'project_id' => 'nullable|integer',
                'due_date' => 'nullable|date',
                'tags'=>'nullable|array'
            ];
        }else{
            return [
                'title' => 'required|string|min:2|max:60',
                'description' => 'nullable|string',
                'priority' => 'required|string',
                'project_id' => 'nullable|integer',
                'due_date' => 'nullable|date',
                'tags'=>'nullable|array'
            ];
        }
    }

    protected function getRedirectUrl()
    {
        return route('tasks.create');
    }
}
