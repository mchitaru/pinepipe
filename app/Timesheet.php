<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

use App\Traits\Invoiceable;

use App\Scopes\CollaboratorTenantScope;

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
        'remark',
        'user_id',
        'created_by'
    ];

    protected $nullable = [
        'rate',
        'project_id', 
        'task_id',
        'started_at'
    ];
    
    public static $SEED = 0;

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CollaboratorTenantScope);

        static::creating(function ($timesheet) {
            if ($user = \Auth::user()) {
                $timesheet->user_id = $user->id;
                $timesheet->created_by = $user->created_by;
            }
        });

        static::deleting(function ($timesheet) {

            $timesheet->invoiceables()->each(function($inv) {
                $inv->delete();
            });
        });
    }

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

    public function getTitleAttribute()
    {
        return ((!empty($this->task)?$this->task->title:__('Timesheet').' ('.\Auth::user()->dateFormat($this->date).' | '.$this->formatTime().')'));
    }

    public function getShortTitleAttribute()
    {
        return (!empty($this->task) ? $this->task->title : (\Auth::user()->dateFormat($this->date, false).' | '.$this->formatTime()));
    }

    public static function createTimesheet($post)
    {
        if(isset($post['task_id']) && !is_numeric($post['task_id'])) {

            //new task
            $task = Task::create(['title' => $post['task_id'],
                                    'project_id' => $post['project_id'],
                                    'priority' => 1]);

            $post['task_id'] = $task->id;
        }

        $timeSheet             = Timesheet::make($post);
        $timeSheet->user_id    = \Auth::user()->id;
        $timeSheet->created_by = \Auth::user()->created_by;
        $timeSheet->save();

        return $timeSheet;
    }

    public function updateTimesheet($post)
    {
        if(isset($post['task_id']) && !is_numeric($post['task_id'])) {

            //new task
            $task = Task::create(['title' => $post['task_id'],
                                    'project_id' => $post['project_id'],
                                    'priority' => 1]);
            $post['task_id'] = $task->id;
        }

        $this->update($post);
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
