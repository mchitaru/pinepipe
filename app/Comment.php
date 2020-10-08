<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Taggable;

use App\Scopes\CollaboratorTenantScope;

class Comment extends Model
{
    use Taggable;
    
    protected $fillable = [
        'comment', 
        'user_id',
        'created_by',
    ];

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CollaboratorTenantScope);

    }
    
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

}
