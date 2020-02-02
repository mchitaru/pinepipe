<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    protected $fillable = [
        'project_id',
        'file_name',
        'file_path',
        'created_by'
    ];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }
}
