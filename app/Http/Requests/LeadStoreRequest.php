<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create lead');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|required|max:20',
            'price' => 'numeric|required',
            'stage_id' => 'integer|required',
            'source_id' => 'integer|required',
            'client_id' => 'integer|nullable',
            'contact_id' => 'integer|nullable',
            'user_id' => 'integer|nullable',
            'notes' => 'string|nullable'
        ];
    }

    protected function getRedirectUrl()
    {
        return route('leads.create');
    }
}
