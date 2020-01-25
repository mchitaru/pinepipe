<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskChecklist extends Model
{
    protected $fillable = [
        'name', 
        'task_id',
        'status',
        'created_by', 
    ];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }
}
