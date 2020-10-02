<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $note = $this->route()->parameter('note');

        return $note->created_by == \Auth::user()->created_by &&
                (\Auth::user()->type == 'company' ||
                $note->user_id == \Auth::user()->id);

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
            'text'=>'required|string|min:3'
        ];
    }

    protected function getRedirectUrl()
    {
        return route('notes.edit');
    }
}
