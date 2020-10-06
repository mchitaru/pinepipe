<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CollaboratorTenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if(\Auth::hasUser() && \Auth::user()->id != 1){

            $builder->where('created_by', \Auth::user()->created_by)
                    ->orWhereIn('created_by', \Auth::user()->collaborators->pluck('id'));            
        }        
    }
}