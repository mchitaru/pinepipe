<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if ($user = \Auth::user()) {
                $article->created_by = $user->creatorId();
            }

            $article->slug = Str::of($article->title)->slug('-');
        });

        static::updating(function ($article) {

            $article->slug = Str::of($article->name)->slug('-');
        });
    }
}
