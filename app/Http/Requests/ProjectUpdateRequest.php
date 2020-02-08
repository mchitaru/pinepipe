<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\URL;

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
        if ($this->isMethod('put'))
        {
            return [
                'name' => 'required|min:2|max:60',
                'start_date' => 'required|date',
                'due_date' => 'required|date',
                'client_id' => 'nullable|integer',
                'user_id' => 'nullable|array',
                'lead_id' => 'nullable|integer',
                'price' => 'required|numeric',
                'description' => 'nullable|string',
            ];
        }else
        {
            return [
                'archived' => 'nullable|boolean'
            ];
        }
    }

    protected function getRedirectUrl()
    {
        if ($this->isMethod('put'))
        {
            $project = $this->route()->parameter('project');

            return route('projects.edit', $project);
        }
        else
        {
            return URL::previous();
        }
    }
}
