<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Lead;

class StageStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::user()->can('create', 'App\Stage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'class' => 'required|string',
            'open' => 'required|boolean',
            'order' => 'required|numeric',
        ];
    }

    protected function getRedirectUrl()
    {
        return route('stages.create');
    }
}
