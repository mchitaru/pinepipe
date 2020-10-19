<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', ['App\Expense', $this->project]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric',
            'date' => 'required',
            'category_id' => 'nullable',
            'project_id' => 'integer|nullable',
            'user_id' => 'integer|required',
            'attachment' => 'mimetypes:image/*,application/pdf|max:2048'
        ];
    }

    protected function getRedirectUrl()
    {
        return route('expenses.create');
    }
}
