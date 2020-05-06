<?php

namespace App\Http\Requests;

use App\Lead;
use Illuminate\Foundation\Http\FormRequest;

class StageUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        switch($this->class) {
            case Lead::class:
                return \Auth::user()->can('edit lead stage');
            default:
                return \Auth::user()->can('edit task stage');
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
            'name' => 'required|string|min:3',
            'open' => 'required|boolean',
        ];
    }

    protected function getRedirectUrl()
    {
        $stage = $this->route()->parameter('stage');
        return route('stages.edit', $stage);
    }
}
