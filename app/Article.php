<?php

namespace App;

use App\Traits\Categorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Article extends Model implements HasMedia
{
    use HasMediaTrait, Categorizable;

    protected $fillable = [
        'title', 
        'content',
        'published',
        'user_id',
        'created_by',
    ];

    protected $nullable = [
        'slug',
        'category_id',
    ];    

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if ($user = \Auth::user()) {

                $article->user_id = $user->id;
                $article->created_by = $user->creatorId();
            }
        });

        static::created(function ($article) {

            $article->slug = Str::of($article->title.' '.$article->id)->slug('-');
            $article->save();
        });

        static::updating(function ($article) {

            $article->slug = Str::of($article->title.' '.$article->id)->slug('-');
        });

        static::deleting(function ($article) {

        });
    }

    public static function createArticle($post)
    {
        $article = Article::create($post);

        return $article;
    }

    public function updateArticle($post)
    {
        $this->update($post);
    }
}
