<?php

namespace App\Http\Requests;

use App\Project;
use Illuminate\Foundation\Http\FormRequest;

class TimesheetStoreRequest extends FormRequest
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
        
        return $this->user()->can('create timesheet');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'nullable|integer',
            'task_id'  => 'nullable|integer',
            'date'  => 'required|date',
            'hours' => 'required|integer',
            'minutes' => 'required|integer',
            'seconds' => 'required|integer',
            'rate' => 'nullable|numeric',
            'remark' => 'nullable|string'    
        ];
    }

    protected function getRedirectUrl()
    {
        return route('timesheets.create');
    }
}
