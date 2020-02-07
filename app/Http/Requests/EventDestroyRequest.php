<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->can('delete event'))
        {
            $event = $this->route()->parameter('event');

            return $event->created_by == \Auth::user()->creatorId() &&
                    $event->user_id == \Auth::user()->id;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    protected function getRedirectUrl()
    {
        return route('calendar.index');
    }
}
