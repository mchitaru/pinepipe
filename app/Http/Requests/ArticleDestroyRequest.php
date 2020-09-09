<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $article = $this->route()->parameter('article');

        return $article->created_by == \Auth::user()->created_by &&
                (\Auth::user()->type == 'company' ||
                $article->user_id == \Auth::user()->id);
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
