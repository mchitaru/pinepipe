<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->can('create', 'App\Note');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'text'=>'required|string|min:3',
            'lead_id' => 'nullable|integer',
            'project_id' => 'nullable|integer',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('notes.create');
    }
}
