<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create event');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|string|min:3',
            'category_id'=>'required|integer',
            'start'=>'required|date_format:Y-m-d H:i',
            'end'=>'required|date_format:Y-m-d H:i',
            // 'busy'=>'required|boolean',
            'notes'=>'nullable|string',
            'user_id'=>'required|integer',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('events.create');
    }
}
