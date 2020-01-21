<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskChecklist extends Model
{
    protected $fillable = [
        'name', 'task_id','created_by', 'status',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
