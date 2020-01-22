<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $fillable = [
        'comment', 'task_id', 'created_by',
    ];

    
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

}
