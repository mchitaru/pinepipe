<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $task = $this->route()->parameter('task');

        if(!$task){

            return $this->user()->can('create task');
        }else{

            return $this->user()->can('edit task');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (!$this->isMethod('patch')){

            if(\Auth::user()->type == 'company') {
                return [
                    'title' => 'required|string|min:2',
                    'priority' => 'required|string',
                    'user_id' => 'nullable|integer',
                    'due_date' => 'required|date',
                    'start_date' => 'required|date',
                    'description' => 'nullable|string',
                    'project_id' => 'nullable|integer'
                ];
            }else{
                return [
                    'title' => 'required|string|min:2',
                    'priority' => 'required|string',
                    'due_date' => 'required|date',
                    'start_date' => 'required|date',
                    'description' => 'nullable|string',
                    'project_id' => 'nullable|integer'
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
        if (!$this->isMethod('patch')){

            $task = $this->route()->parameter('task');

            if($task){

                return route('tasks.edit', $task);
            }else{

                return route('tasks.create');
            }
        }else{

            return route('tasks.index');
        }
    }
}
