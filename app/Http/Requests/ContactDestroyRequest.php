<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->can('delete contact'))
        {
            $contact = $this->route()->parameter('contact');

            return $contact->created_by == \Auth::user()->creatorId() &&
                    $contact->user_id == \Auth::user()->id;
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
        return route('clients.index').'#contacts';
    }
}
