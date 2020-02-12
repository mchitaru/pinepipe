<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class Task extends Model
{
    use NullableFields;

    protected $fillable = [
        'title',
        'priority',
        'description',
        'due_date',
        'start_date',
        'project_id',
        'milestone_id',
        'order',
        'stage_id',
    ];

    protected $nullable = [
        'project_id',
        'milestone_id',
        'description'
	];

    public static $SEED_PROJECT = 50;
    public static $SEED_FREE = 10;

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function stage()
    {
        return $this->belongsTo('App\ProjectStage');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_tasks');
    }

    public function comments()
    {
        return $this->hasMany('App\TaskComment','task_id','id');
    }

    public function files()
    {
        return $this->hasMany('App\TaskFile','task_id','id');
    }

    public function checklist()
    {
        return $this->hasMany('App\TaskChecklist','task_id','id');
    }

    public function milestone()
    {
        return $this->hasOne('App\Milestone','id','milestone_id');
    }

    public function timesheets()
    {
        return $this->hasMany('App\Timesheet', 'task_id', 'id');
    }    

    public function getCompleteChecklistCount()
    {
        $count = 0;
        foreach($this->checklist as $check) {
            if($check->status) $count++;
        }

        return $count;    
    }

    public function getTotalChecklistCount()
    {
        return $this->checklist->count();
    }

    public static function getProgressColor($percentage)
    {
        $label='';
        if($percentage<=15){
            $label = 'bg-danger';
        }else if ($percentage > 15 && $percentage <= 33) {
            $label = 'bg-warning';
        } else if ($percentage > 33 && $percentage <= 70) {
            $label = 'bg-primary';
        } else {
            $label = 'bg-success';
        }

        return $label;
    }

    public static function createTask($post)
    {
        $stage = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->first();

        $post['stage_id']   = $stage->id;

        $task               = Task::make($post);
        $task->created_by  = \Auth::user()->creatorId();
        $task->order = $stage->tasks->count();
        $task->save();

        if(isset($post['user_id'])){

            $users = $post['user_id'];
        }else{

            $users = collect();
        }

        if(\Auth::user()->type != 'company')
        {        
            $users->prepend(\Auth::user()->id);
        }

        $task->users()->sync($users);

        ActivityLog::createTask($task);

        return $task;
    }

    public function updateTask($post)
    {
        $this->update($post);

        if(isset($post['user_id']))
        {
            $users = $post['user_id'];
        }else{

            $users = collect();
        }

        if(\Auth::user()->type != 'company' &&
            empty($users))
        {        
            $users->prepend(\Auth::user()->id);
        }


        $this->users()->sync($users);

        ActivityLog::updateTask($this);
    }

    public function detachTask()
    {
        $this->users()->detach();

        $this->comments()->delete();
        $this->checklist()->delete();

        $dir = storage_path('app/public/tasks/');

        foreach($this->files as $file)
        {
            File::delete($dir . $file->file);
        }

        $this->files()->delete();

        ActivityLog::deleteTask($this);
    }

}
