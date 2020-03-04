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
        if($this->user()->can('edit lead'))
        {
            $lead = $this->route()->parameter('lead');

            return $lead->created_by == \Auth::user()->creatorId() &&
                    (\Auth::user()->type == 'company' ||
                    $lead->user_id == \Auth::user()->id);
        }

        return false;
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
            'client_id' => 'integer|required',
            'user_id' => 'integer|nullable',
            'notes' => 'string|nullable'
        ];
    }

    protected function getRedirectUrl()
    {
        $lead = $this->route()->parameter('lead');
        return route('leads.edit', $lead);
    }
}
