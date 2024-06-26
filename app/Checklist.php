<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Scopes\CollaboratorTenantScope;

class Checklist extends Model
{
    protected $fillable = [
        'title', 
        'status',
        'order',
        'user_id',
        'created_by'
    ];

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CollaboratorTenantScope);

        static::creating(function ($checklist) {
            if ($user = \Auth::user()) {
                $checklist->user_id = $user->id;
                $checklist->created_by = $user->created_by;
            }
        });

        static::deleting(function ($article) {

        });
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function checklistable()
    {
        return $this->morphTo();
    }
}
