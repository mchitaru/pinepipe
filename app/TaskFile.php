<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskFile extends Model
{
    protected $fillable = [
        'task_id',
        'file_name',
        'file_path',
        'created_by'
    ];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }
}
