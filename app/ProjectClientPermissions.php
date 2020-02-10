<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectClientPermissions extends Model
{
    protected $fillable = [
        'project_id',
        'permissions'
    ];
}
