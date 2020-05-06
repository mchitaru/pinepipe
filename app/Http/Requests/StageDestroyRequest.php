<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\URL;

class StageDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $stage = $this->route()->parameter('stage');

        if(!$stage->leads->isEmpty() || !$stage->tasks->isEmpty()) {

            return false;
        }

        switch($this->class) {
            case Lead::class:
                return \Auth::user()->can('delete lead stage');
            default:
                return \Auth::user()->can('delete task stage');
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
        return URL::previous();
    }
}
