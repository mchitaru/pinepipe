<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->can('update', $this->task))
        {
            $task = $this->task;

            if ($this->isMethod('put'))
            {    
                //the task or project was created by this company
                return $task->created_by == \Auth::user()->created_by ||
                        $task->project && $task->project->created_by == \Auth::user()->created_by;

            }else{

                //if he tries to change status, the task or project was created by this company or is assigned to the user
                return $task->created_by == \Auth::user()->created_by ||
                        $task->project && $task->project->created_by == \Auth::user()->created_by ||
                        $task->users->contains(\Auth::user()->id);
            }
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
        if ($this->isMethod('put'))
        {
            return [
                'title' => 'required|string|min:2|max:60',
                'description' => 'nullable|string',
                'priority' => 'required|numeric',
                'users' => (\Auth::user()->type == 'company') ? 'nullable|array' : 'required|array', //+
                'project_id' => 'nullable|integer',
                'due_date' => 'nullable|date',
                'stage_id' => 'integer',
                'tags' => 'nullable|array'
            ];
        }else{

            return [
                'stage_id' => 'nullable|integer',
                'closed' => 'nullable|integer',
                'order' => 'nullable|integer',
            ];
        }
    }

    protected function getRedirectUrl()
    {
        if ($this->isMethod('put'))
        {
            $task = $this->route()->parameter('task');

            return route('tasks.edit', $task);
        }else{

            return URL::previous();
        }
    }
}
