<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\Scopes\CollaboratorTenantScope;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CollaboratorTenantScope);

        static::creating(function ($tag) {
            if ($user = \Auth::user()) {
                $tag->user_id = $user->id;
                $tag->created_by = $user->created_by;
            }
        });

        static::created(function ($tag) {

            $tag->slug = Str::of($tag->name.' '.$tag->id)->slug('-');
            $tag->save();
        });

        static::updating(function ($tag) {

            $tag->slug = Str::of($tag->name.' '.$tag->id)->slug('-');
        });

        static::deleting(function ($tag) {

            $tag->tasks()->detach();
            $tag->contacts()->detach();

        });
    }

    public function tasks()
    {
        return $this->morphedByMany('App\Task', 'taggable');
    }

    public function contacts()
    {
        return $this->morphedByMany('App\Contact', 'taggable');
    }
}
