<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'project_id', 
        'user_id', 
        'task_id',
        'date',
        'hours',
        'remark'
    ];

    public function task()
    {
        return $this->belongsTo('App\Task', 'id', 'task_id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'user_id');
    }
}
