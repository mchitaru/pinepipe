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
                    'title' => 'required',
                    'priority' => 'required',
                    'user_id' => 'nullable',
                    'due_date' => 'required',
                    'start_date' => 'required',
                    'description' => 'nullable',
                ];
            }else{
                return [
                    'title' => 'required',
                    'priority' => 'required',
                    'due_date' => 'required',
                    'start_date' => 'required',
                    'description' => 'nullable',
                ];
            }
        }else{

            return [
                'status' => 'string',
                'stage_id' => 'numeric'
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
