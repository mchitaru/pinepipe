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
        if($this->user()->can('edit timesheet'))
        {
            $timesheet = $this->route()->parameter('timesheet');

            return $timesheet->created_by == \Auth::user()->creatorId() &&
                    (\Auth::user()->type == 'company' ||
                    $timesheet->user_id == \Auth::user()->id);
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
