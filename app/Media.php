<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($media) {
            if ($user = \Auth::user()) {
                $media->user_id = $user->id;
                $media->created_by = $user->creatorId();
            }
        });

        static::deleting(function ($article) {

        });
    }

    /**
     * User relationship (one-to-one)
     * @return App\User
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}