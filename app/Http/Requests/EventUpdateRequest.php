<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->event);
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
            'start'=>'required|date_format:Y-m-d H:i',
            'end'=>'required|date_format:Y-m-d H:i',
            'description'=>'nullable|string',
            'lead_id'=>'nullable|integer',
            'users'=>'required|array',
            'allday'=>'required|boolean',
        ];
    }

    protected function getRedirectUrl()
    {
        $event = $this->route()->parameter('event');
        return route('events.edit', $event);
    }
}
