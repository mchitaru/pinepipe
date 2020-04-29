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
            'name' => 'string|required|max:60',
            'price' => 'numeric|nullable',
            'stage_id' => 'integer|required',
            'category_id' => 'numeric|nullable',
            'client_id' => 'integer|required',
            'contact_id' => 'integer|nullable',
            'user_id' => 'integer|nullable',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('leads.create');
    }
}
