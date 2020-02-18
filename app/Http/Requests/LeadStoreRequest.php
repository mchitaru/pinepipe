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
        if(\Auth::user()->type == 'company')
        {
            return [
                'name' => 'required|max:20',
                'price' => 'required',
                'stage_id' => 'required',
                'user_id' => 'required',
                'client_id' => 'required',
                'source_id' => 'required',
            ];
        }
        else
        {
            return [
                'name' => 'required|max:20',
                'price' => 'required',
                'stage_id' => 'required',
                'source_id' => 'required',
                'client_id' => 'required',
            ];
        }
    }

    protected function getRedirectUrl()
    {
        return route('leads.create');
    }
}
