<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media as BaseMedia;

use App\Scopes\CompanyTenantScope;

class Media extends BaseMedia
{
    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyTenantScope);

        static::creating(function ($media) {
            if ($user = \Auth::user()) {
                $media->user_id = $user->id;
                $media->created_by = $user->created_by;
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