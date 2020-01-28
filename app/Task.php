<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'priority',
        'description',
        'due_date',
        'start_date',
        'project_id',
        'milestone_id',
        'status',
        'order',
        'stage_id',
    ];

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

    public function comments(){
        return $this->hasMany('App\TaskComment','task_id','id')->orderBy('id','DESC');
    }

    public function taskFiles(){
        return $this->hasMany('App\TaskFile','task_id','id')->orderBy('id','DESC');
    }

    public function taskCheckList(){
        return $this->hasMany('App\TaskChecklist','task_id','id')->orderBy('id','DESC');
    }

    public function taskCompleteCheckListCount(){
        return $this->hasMany('App\TaskChecklist','task_id','id')->where('status','=','1')->count();
    }

    public function taskTotalCheckListCount(){
        return $this->hasMany('App\TaskChecklist','task_id','id')->count();
    }
    public function milestone(){
        return $this->hasOne('App\Milestone','id','milestone_id');
    }

    public function timesheets()
    {
        return $this->hasMany('App\Timesheet', 'task_id', 'id');
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
        $post['stage_id']   = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->first()->id;
        $post['project_id'] = !empty($post['project_id']) ? $post['project_id'] : null;

        $task               = Task::make($post);
        $task->created_by  = \Auth::user()->creatorId();
        $task->save();

        $users = null;
        if(isset($post['user_id'])){

            $users = $post['user_id'];
        }

        if(\Auth::user()->type != 'company')
        {        
            $users->prepend(\Auth::user()->id);
        }

        $task->users()->sync($users);

        ActivityLog::createTask($task);

        return $task;
    }

}
