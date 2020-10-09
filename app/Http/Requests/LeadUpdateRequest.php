<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->lead);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('put'))
        {
            return [
                'name' => 'string|required|max:60',
                'price' => 'numeric|nullable',
                'stage_id' => 'integer|required',
                'category_id' => 'nullable',
                'client_id' => 'required',
                'contact_id' => 'nullable',
            ];
        }else{

            return [
                'archived' => 'nullable|boolean'
            ];
        }
    }

    protected function getRedirectUrl()
    {
        if ($this->isMethod('put'))
        {
            $lead = $this->route()->parameter('lead');
            return route('leads.edit', $lead);
        }
        else
        {
            return URL::previous();
        }
    }
}
