<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Scopes\TenantScope;

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

        static::addGlobalScope(new TenantScope);

        static::creating(function ($checklist) {
            if ($user = \Auth::user()) {
                $checklist->user_id = $user->id;
                $checklist->created_by = $user->created_by;
            }
        });

        static::deleting(function ($article) {

        });
    }

    public function checklistable()
    {
        return $this->morphTo();
    }
}
