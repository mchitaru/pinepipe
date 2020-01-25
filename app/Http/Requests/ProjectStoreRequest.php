<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->can('create project');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:20',
            'start_date' => 'required',
            'due_date' => 'required',
            'client_id' => 'nullable',
            'user_id' => 'nullable',
            'lead_id' => 'nullable',
            'price' => 'nullable',
            'description' => 'nullable',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('projects.create');
    }
}
