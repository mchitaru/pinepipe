<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('edit task');
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
            if(\Auth::user()->type == 'company') {
                return [
                    'title' => 'required|string|min:2',
                    'description' => 'nullable|string',
                    'priority' => 'required|string',
                    'user_id' => 'nullable|array', //+
                    'project_id' => 'nullable|integer',
                    'start_date' => 'required|date',
                    'due_date' => 'required|date',
                ];
            }else{
                return [
                    'title' => 'required|string|min:2',
                    'description' => 'nullable|string',
                    'priority' => 'required|string',
                    'project_id' => 'nullable|integer',
                    'start_date' => 'required|date',
                    'due_date' => 'required|date',
                ];
            }
        }else{

            return [
                'status' => 'string',
                'stage_id' => 'integer'
            ];
        }
    }

    protected function getRedirectUrl()
    {
        if ($this->isMethod('put')){

            $task = $this->route()->parameter('task');

            return route('tasks.edit', $task);
        }else{

            return route('tasks.index');
        }
    }
}
