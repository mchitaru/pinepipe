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
        'rate',
        'remark'
    ];

    protected $nullable = [
        'project_id', 
        'task_id',
    ];
    
    public static $SEED = 20;

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

    public static function createTimesheet($post)
    {
        $timeSheet             = Timesheet::make($post);
        $timeSheet->user_id    = \Auth::user()->id;
        $timeSheet->created_by = \Auth::user()->creatorId();
        $timeSheet->save();

        dump($timeSheet);

        return $timeSheet;
    }

    public function updateTimesheet($post)
    {
        $this->update($post);
        dump($this);
    }

    public function detachTimesheet()
    {
    }

}
