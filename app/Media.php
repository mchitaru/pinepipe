<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media as BaseMedia;

use App\Traits\Actionable;

class Media extends BaseMedia
{
    use Actionable;

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

        static::deleting(function ($media) {
            $media->activities()->delete();
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