<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(\Auth::user()->can('edit project'))
        {
            $project = $this->route()->parameter('project');

            return ($project->created_by == \Auth::user()->creatorId());
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
            'name' => 'required|min:2|max:20',
            'start_date' => 'required|date',
            'due_date' => 'required|date',
            'client_id' => 'nullable|integer',
            'user_id' => 'nullable|integer',
            'lead_id' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('projects.edit');
    }
}
