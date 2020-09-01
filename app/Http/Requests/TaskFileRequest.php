<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|mimetypes:image/*,text/*,font/*,application/*|max:10240'
        ];
    }

    protected function getRedirectUrl()
    {
        $task = $this->route()->parameter('task');

        return route('tasks.file.index', $task->id);
    }
}
