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
        return $this->user()->can('create', 'App\Event');
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
            'project_id'=>'nullable|integer',
            'users'=>'required|array',
            'allday'=>'required|boolean',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('events.create');
    }
}
