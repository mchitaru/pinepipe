<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class Project extends Model
{
    use NullableFields;

    protected $fillable = [
        'name',
        'description',
        'price',
        'client_id',
        'lead_id',
        'archived',
        'start_date',
        'due_date',
    ];

    protected $nullable = [
        'client_id',
        'user_id',
        'lead_id',
        'description',
    ];

    protected $hidden = [

    ];

    public static $SEED = 20;

    public function tasks()
    {
        return $this->hasMany('App\Task', 'project_id', 'id');
    }

    public function client()
    {
        return $this->hasOne('App\User', 'id', 'client_id');
    }

    public function milestones()
    {
        return $this->hasMany('App\Milestone', 'project_id', 'id');
    }

    public function timesheets()
    {
        return $this->hasMany('App\Timesheet', 'project_id', 'id');
    }

    public function activities()
    {
        return $this->hasMany('App\ActivityLog', 'project_id', 'id')->orderBy('id', 'desc');
    }

    public function files()
    {
        return $this->hasMany('App\ProjectFile', 'project_id', 'id');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice', 'project_id', 'id');
    }

    public function expenses()
    {
        return $this->hasMany('App\Expense', 'project_id', 'id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_projects');
    }

    public function permissions()
    {
        return $this->hasOne('App\ProjectClientPermissions', 'project_id', 'id');
    }

    public function stages()
    {
        return ProjectStage::with(['tasks' => function ($query) 
        {
            $query->where('project_id', '=', $this->id);
        }])
        ->where('created_by', '=', \Auth::user()->creatorId())
        ->orderBy('order', 'ASC');
    }    

    public function computeStatistics($last_stage_id)
    {   
        $this->progress = 0;
        $this->completed_tasks = 0;  

        if(!$this->tasks->isEmpty())
        {
            foreach($this->tasks as $task)
            {
                if($task->stage_id == $last_stage_id)
                    $this->completed_tasks++;
            }

            $this->progress = intval(($this->completed_tasks / $this->tasks->count()) * 100);
        }
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
            $active  = Project::where('archived', '=', false)->where('created_by', '=', \Auth::user()->id)->count();
            $completed = Project::where('archived', '=', true)->where('created_by', '=', \Auth::user()->id)->count();
            $total     = $active + $completed;

            $projectData['active']  = ($total != 0 ? number_format(($active / $total) * 100, 2) : 0);
            $projectData['completed'] = ($total != 0 ? number_format(($completed / $total) * 100, 2) : 0);
        }
        else if(\Auth::user()->type == 'client')
        {
            $active  = Project::where('archived', '=', false)->where('client_id', '=', \Auth::user()->id)->count();
            $completed = Project::where('archived', '=', true)->where('client_id', '=', \Auth::user()->id)->count();
            $total     = $active + $completed;

            $projectData['active']  = ($total != 0 ? number_format(($active / $total) * 100, 2) : 0);
            $projectData['completed'] = ($total != 0 ? number_format(($completed / $total) * 100, 2) : 0);
        }
        else
        {
            $active  = UserProject::join('projects', 'user_projects.project_id', '=', 'projects.id')->where('projects.archived', '=', false)->where('user_id', '=', \Auth::user()->id)->count();
            $completed = UserProject::join('projects', 'user_projects.project_id', '=', 'projects.id')->where('projects.archived', '=', true)->where('user_id', '=', \Auth::user()->id)->count();
            $total     = $active + $completed;

            $projectData['active']  = ($total != 0 ? number_format(($active / $total) * 100, 2) : 0);
            $projectData['completed'] = ($total != 0 ? number_format(($completed / $total) * 100, 2) : 0);
        }

        return $projectData;
    }

    public function createTask($post)
    {
        $post['project_id'] = $this->id;
        $post['stage_id']   = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->first()->id;
        $task               = Task::make($post);
        $task->created_by   = \Auth::user()->creatorId();
        $task->save();

        if(isset($post['user_id']))
        {
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

    public static function createProject($post)
    {
        $project              = Project::make($post);
        $project->created_by  = \Auth::user()->creatorId();
        $project->save();

        if(isset($post['user_id']))
        {
            $users = $post['user_id'];
        }else{

            $users = collect();
        }

        if(\Auth::user()->type != 'company')
        {        
            $users->prepend(\Auth::user()->id);
        }

        $project->users()->sync($users);

        $permissions = Project::$permission;
        ProjectClientPermissions::create(
            [
                'client_id' => $project->client_id,
                'project_id' => $project->id,
                'permissions' => implode(',', $permissions),
            ]
        );

        ActivityLog::createProject($project);

        return $project;
    }

    public function updateProject($post)
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

        ProjectClientPermissions::where('client_id','=',$this->client_id)->where('project_id','=', $this->id)->delete();
        $permissions = Project::$permission;
        ProjectClientPermissions::create(
            [
                'client_id' => $this->client_id,
                'project_id' => $this->id,
                'permissions' => implode(',', $permissions),
            ]
        );

        ActivityLog::updateProject($this);
    }

    public function detachProject()
    {
        $users = collect();

        $this->users()->sync($users);

        $this->milestones()->delete();
        // ActivityLog::where('project_id', $this->id)->delete();

        $dir = storage_path('app/'.\Auth::user()->creatorId());

        foreach($this->files as $file)
        {
            File::delete($dir . $file->file);
        }

        $this->files()->delete();

        Invoice::where('project_id', $this->id)->update(array('project_id' => 0));

        foreach($this->tasks as $task)
        {
            $task->detachTask();

            $task->delete();
        }

        ActivityLog::deleteProject($this);
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

    public static $permission = [
        // '',
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
