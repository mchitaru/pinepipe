<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskFile extends Model
{
    protected $fillable = [
        'file',
        'name',
        'extension',
        'file_size',
        'task_id',
        'user_type',
        'created_by'
    ];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }
}
