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
            'name' => 'required|min:2|max:60',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'client_id' => 'required|integer',
            'user_id' => 'nullable|array',
            'lead_id' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('projects.create');
    }
}
