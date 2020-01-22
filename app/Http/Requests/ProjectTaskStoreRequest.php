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
        if(\Auth::user()->type == 'company') {
            return [
                'title' => 'required',
                'priority' => 'required',
                'assign_to' => 'required',
                'due_date' => 'required',
                'start_date' => 'required'
            ];
        }else{
            return [
                'title' => 'required',
                'priority' => 'required',
                'due_date' => 'required',
                'start_date' => 'required',
            ];
        }
    }

    protected function getRedirectUrl()
    {
        return route('tasks.create');
    }
}
