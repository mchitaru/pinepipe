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
        return $this->user()->can('edit event');
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
            'category'=>'required|string',
            'start'=>'required|date_format:Y-m-d H:i',
            'end'=>'required|date_format:Y-m-d H:i',
            // 'busy'=>'required|boolean',
            'notes'=>'nullable|string',
            'lead_id'=>'nullable|integer',
            'users'=>'required|array',
        ];
    }

    protected function getRedirectUrl()
    {
        $event = $this->route()->parameter('event');
        return route('events.edit', $event);
    }
}
