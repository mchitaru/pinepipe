<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimesheetUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $timesheet = $this->timesheet;

        if($this->user()->can('update', $timesheet))
        {
            //the timesheet or project was created by this company
            return ($timesheet->created_by == \Auth::user()->created_by ||
                    $timesheet->project && $timesheet->project->created_by == \Auth::user()->created_by) &&
                    (\Auth::user()->type == 'company' || $timesheet->user_id == \Auth::user()->id);
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
            'project_id' => 'nullable|integer',
            'task_id'  => 'nullable',
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
        $timesheet = $this->route()->parameter('timesheet');
        return route('timesheets.edit', $timesheet);
    }
}
