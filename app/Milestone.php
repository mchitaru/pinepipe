<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Scopes\CompanyTenantScope;

class Milestone extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'status',
        'cost',
        'summary'
    ];

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyTenantScope);
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }
}
