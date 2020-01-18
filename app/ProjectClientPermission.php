<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectClientPermission extends Model
{
    protected $fillable = [
        'client_id', 'project_id','permissions'
    ];
}
