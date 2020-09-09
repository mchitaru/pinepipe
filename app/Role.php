<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as BaseRole;

use App\Scopes\ExtendedTenantScope;

class Role extends BaseRole
{
    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExtendedTenantScope);

        static::creating(function ($role) {
            if ($user = \Auth::user()) {
                $role->user_id = $user->id;
                $role->created_by = $user->created_by;
            }
        });
    }
}