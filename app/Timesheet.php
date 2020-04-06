<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

use App\Traits\Invoiceable;

class Timesheet extends Model
{
    use NullableFields;
    use Invoiceable;

    protected $fillable = [
        'project_id', 
        'user_id', 
        'task_id',
        'date',
        'hours',
        'minutes',
        'seconds',
        'rate',
        'remark'
    ];

    protected $nullable = [
        'project_id', 
        'task_id',
        'started_at'
    ];
    
    public static $SEED = 0;

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

        return $timeSheet;
    }

    public function updateTimesheet($post)
    {
        $this->update($post);
    }

    public function detachTimesheet()
    {
    }

    public function computeTime()
    {
        return Carbon::now()->diffInSeconds($this->started_at) + $this->hours*3600 + $this->minutes*60 + $this->seconds;        
    }

    public function formatTime()
    {
        $seconds = $this->computeTime();

        $hours = (int)floor($seconds / 3600); 
        $minutes = (int)floor(($seconds % 3600) / 60);
        $seconds = ($seconds % 3600) % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    public function isStarted()
    {
        return $this->started_at != null;
    }

    public function start()
    {
        $this->started_at = Carbon::now();
        $this->save();
    }

    public function stop()
    {
        $stop = Carbon::now();

        $seconds = $stop->diffInSeconds($this->started_at) + $this->hours*3600 + $this->minutes*60 + $this->seconds;

        $this->hours = (int)floor($seconds / 3600); 
        $this->minutes = (int)floor(($seconds % 3600) / 60);
        $this->seconds = ($seconds % 3600) % 60;

        $this->started_at = null;
        $this->save();
    }
}
