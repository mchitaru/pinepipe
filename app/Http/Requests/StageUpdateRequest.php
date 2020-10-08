<?php

namespace App\Http\Requests;

use App\Lead;
use App\Stage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\URL;

class StageUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->stage){

            return \Auth::user()->can('update', $this->stage);
        }

        return \Auth::user()->can('create', 'App\Stage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->isMethod('put')){

            return [
                'name' => 'required|string|min:3',
                'open' => 'required|boolean',
            ];
        }else {

            return [
                'order' => 'required|array',
            ];
        }
    }

    protected function getRedirectUrl()
    {
        if($this->isMethod('put')){

            $stage = $this->route()->parameter('stage');
            return route('stages.edit', $stage);
        }

        return URL::previous();
    }
}
