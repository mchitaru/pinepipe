<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $article = $this->article;

        if($this->user()->can('update', $article))
        {
            return $article->created_by == \Auth::user()->created_by &&
                    (\Auth::user()->type == 'company' ||
                        $article->user_id == \Auth::user()->id);
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
            'title'=>'nullable|string|min:3',
            'content' => 'required|string',
            'category_id' => 'nullable|integer',
            'published' => 'required|boolean',
            'path' => 'required|string'
        ];
    }

    protected function getRedirectUrl()
    {
        return route('articles.edit');
    }
}
