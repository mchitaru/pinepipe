<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->can('update', $this->expense))
        {
            $expense = $this->route()->parameter('expense');

            return $expense->created_by == \Auth::user()->created_by &&
                    (\Auth::user()->type == 'company' ||
                    $expense->user_id == \Auth::user()->id);
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
            'amount' => 'required',
            'date' => 'required',
            'category_id' => 'nullable',
            'project_id' => 'integer|nullable',
            'user_id' => 'integer|required',
            'attachment' => 'mimetypes:image/*,application/pdf|max:2048'
        ];
    }

    protected function getRedirectUrl()
    {
        $expense = $this->route()->parameter('expense');
        return route('expenses.edit', $expense);
    }    
}
