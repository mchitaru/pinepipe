<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class Timesheet extends Model
{
    use NullableFields;

    protected $fillable = [
        'project_id', 
        'user_id', 
        'task_id',
        'date',
        'hours',
        'remark'
    ];

    protected $nullable = [
        'project_id', 
        'task_id',
    ];
    
    public static $SEED = 500;

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
