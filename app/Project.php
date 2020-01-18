<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'price',
        'start_date',
        'due_date',
        'client',
        'description',
        'label',
        'status',
    ];


    protected $hidden = [

    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function label()
    {
        return $this->hasOne('App\Label', 'id', 'label')->first();
    }

    public function client()
    {
        return $this->hasOne('App\User', 'id', 'client')->first();
    }

    public function milestones()
    {
        return $this->hasMany('App\Milestone', 'project_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany('App\ActivityLog', 'project_id', 'id')->orderBy('id', 'desc');
    }

    public function files()
    {
        return $this->hasMany('App\ProjectFile', 'project_id', 'id');
    }

    public function countTask()
    {
        return Task::where('project_id', '=', $this->id)->count();
    }

    public function countTaskComments()
    {
        return Task::join('comments', 'comments.task_id', '=', 'tasks.id')->where('project_id', '=', $this->id)->count();
    }

    public function project_expenses()
    {
        return Expense::where('project', '=', $this->id)->sum('amount');
    }

    public function project_user()
    {
        return UserProject::select('user_projects.*', 'users.name', 'users.avatar', 'users.email', 'users.type')->join('users', 'users.id', '=', 'user_projects.user_id')->where('project_id', '=', $this->id)->whereNotIn('user_id', [$this->created_by])->get();
    }

    public function user_project_total_task($project_id, $user_id)
    {
        return Task::where('project_id', '=', $project_id)->where('assign_to', '=', $user_id)->count();
    }

    public function user_project_complete_task($project_id, $user_id, $last_stage_id)
    {
        return Task::where('project_id', '=', $project_id)->where('assign_to', '=', $user_id)->where('stage', '=', $last_stage_id)->count();
    }

    public function project_total_task($project_id)
    {
        return Task::where('project_id', '=', $project_id)->count();
    }

    public function project_complete_task($project_id, $last_stage_id)
    {
        return Task::where('project_id', '=', $project_id)->where('stage', '=', $last_stage_id)->count();
    }

    public function project_last_stage()
    {
        return ProjectStage::where('created_by', '=', $this->created_by)->orderBy('order', 'desc')->first();
    }

    public function client_project_permission()
    {
        return ProjectClientPermission::where('project_id', $this->id)->where('client_id', $this->client)->first();
    }

    public function getProjectProgress()
    {
        $project_last_stage = ($this->project_last_stage($this->id))?$this->project_last_stage($this->id)->id:'';
        $total_task = $this->project_total_task($this->id);
        $completed_task = $this->project_complete_task($this->id, $project_last_stage);
        
        $percentage=0;
        if($total_task!=0){
            $percentage = intval(($completed_task / $total_task) * 100);
        }

        return $percentage;
    }

    public static function getProgressColor($percentage)
    {
        $label='';
        if($percentage<=15){
            $label='bg-danger';
        }else if ($percentage > 15 && $percentage <= 33) {
            $label='bg-warning';
        } else if ($percentage > 33 && $percentage <= 70) {
            $label='bg-primary';
        } else {
            $label='bg-success';
        }

        return $label;
    }

    public static function getProjectStatus()
    {

        $projectData = [];
        if(\Auth::user()->type == 'company')
        {
            $on_going  = Project::where('status', '=', 'on_going')->where('created_by', '=', \Auth::user()->id)->count();
            $on_hold   = Project::where('status', '=', 'on_hold')->where('created_by', '=', \Auth::user()->id)->count();
            $completed = Project::where('status', '=', 'completed')->where('created_by', '=', \Auth::user()->id)->count();
            $total     = $on_going + $on_hold + $completed;

            $projectData['on_going']  = ($total != 0 ? number_format(($on_going / $total) * 100, 2) : 0);
            $projectData['on_hold']   = ($total != 0 ? number_format(($on_hold / $total) * 100, 2) : 0);
            $projectData['completed'] = ($total != 0 ? number_format(($completed / $total) * 100, 2) : 0);
        }
        else if(\Auth::user()->type == 'client')
        {
            $on_going  = Project::where('status', '=', 'on_going')->where('client', '=', \Auth::user()->id)->count();
            $on_hold   = Project::where('status', '=', 'on_hold')->where('client', '=', \Auth::user()->id)->count();
            $completed = Project::where('status', '=', 'completed')->where('client', '=', \Auth::user()->id)->count();
            $total     = $on_going + $on_hold + $completed;

            $projectData['on_going']  = ($total != 0 ? number_format(($on_going / $total) * 100, 2) : 0);
            $projectData['on_hold']   = ($total != 0 ? number_format(($on_hold / $total) * 100, 2) : 0);
            $projectData['completed'] = ($total != 0 ? number_format(($completed / $total) * 100, 2) : 0);
        }
        else
        {
            $on_going  = UserProject::join('projects', 'user_projects.project_id', '=', 'projects.id')->where('projects.status', '=', 'on_going')->where('user_id', '=', \Auth::user()->id)->count();
            $on_hold   = UserProject::join('projects', 'user_projects.project_id', '=', 'projects.id')->where('projects.status', '=', 'on_hold')->where('user_id', '=', \Auth::user()->id)->count();
            $completed = UserProject::join('projects', 'user_projects.project_id', '=', 'projects.id')->where('projects.status', '=', 'completed')->where('user_id', '=', \Auth::user()->id)->count();
            $total     = $on_going + $on_hold + $completed;

            $projectData['on_going']  = ($total != 0 ? number_format(($on_going / $total) * 100, 2) : 0);
            $projectData['on_hold']   = ($total != 0 ? number_format(($on_hold / $total) * 100, 2) : 0);
            $projectData['completed'] = ($total != 0 ? number_format(($completed / $total) * 100, 2) : 0);
        }

        return $projectData;
    }

    static function humanFileSize($bytes, $decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }


    public static $status = [
        'incomplete' => 'Incomplete',
        'complete' => 'Complete',
    ];

    public static $priority       = [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
    ];
    public static $project_status = [
        'on_going' => 'On Going',
        'on_hold' => 'On Hold',
        'completed' => 'Completed',
    ];

    public static $permission = [
        '',
        'show activity',
        'show milestone',
        'create milestone',
        'edit milestone',
        'delete milestone',
        'show task',
        'create task',
        'edit task',
        'delete task',
        'move task',
        'create checklist',
        'edit checklist',
        'delete checklist',
        'show checklist',
        'show uploading',
        'manage timesheet',
        'create timesheet',
        'edit timesheet',
        'delete timesheet'
    ];



}
