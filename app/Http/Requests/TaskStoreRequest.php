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
        return $this->user()->can('create task');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(\Auth::user()->type == 'company') {
            return [
                'title' => 'required',
                'priority' => 'required',
                'assign_to' => 'required',
                'due_date' => 'required',
                'start_date' => 'required',
            ];
        }else{
            return [
                'title' => 'required',
                'priority' => 'required',
                'due_date' => 'required',
                'start_date' => 'required',
            ];
        }
    }

    protected function getRedirectUrl()
    {
        // $task = $this->route()->parameter('task');

        // if($task){
        //     return route('tasks.edit', $task);
        // }else{
            return route('tasks.create');
        // }
    }
}
