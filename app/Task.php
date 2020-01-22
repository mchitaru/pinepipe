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
        'assign_to',
        'project_id',
        'milestone_id',
        'status',
        'order',
        'stage',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function task_user(){
        return $this->hasOne('App\User','id','assign_to');
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

}
