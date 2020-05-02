<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    public function tasks()
    {
        return $this->morphedByMany('App\Task', 'taggable');
    }

    public function contacts()
    {
        return $this->morphedByMany('App\Contact', 'taggable');
    }

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if ($user = \Auth::user()) {
                $tag->user_id = $user->id;
                $tag->created_by = $user->creatorId();
            }

            $tag->slug = Str::of($tag->name)->slug('-');
        });

        static::updating(function ($tag) {

            $tag->slug = Str::of($tag->name)->slug('-');
        });
    }
}
