<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use App\Traits\Actionable;
use App\Traits\Taggable;

class Project extends Model implements HasMedia
{
    use NullableFields, HasMediaTrait, Actionable, Taggable;

    protected $fillable = [
        'name',
        'description',
        'price',
        'client_id',
        'lead_id',
        'archived',
        'start_date',
        'due_date',
        'user_id',
        'created_by'
    ];

    protected $nullable = [
        'client_id',
        'user_id',
        'lead_id',
        'description',
        'start_date',
        'due_date',
    ];

    protected $hidden = [

    ];

    public static $SEED = 10;


    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if ($user = \Auth::user()) {
                $project->user_id = $user->id;
                $project->created_by = $user->creatorId();
            }
        });

        self::deleting(function ($project) {

            $project->users()->detach();

            $project->expenses()->each(function($expense) {
                $expense->delete();
             });

            $project->milestones()->each(function($milestone) {
                $milestone->delete();
            });

            $project->timesheets()->each(function($timesheet) {
                $timesheet->delete();
            });

            $project->invoices()->each(function($invoice) {
                $invoice->delete();
            });

            $project->tasks()->each(function($task) {
                $task->delete();
            });

            $project->tags()->detach();

            $project->activities()->delete();
        });
    }


    public function tasks()
    {
        return $this->hasMany('App\Task', 'project_id', 'id');
    }

    public function client()
    {
        return $this->hasOne('App\Client', 'id', 'client_id');
    }

    public function lead()
    {
        return $this->hasOne('App\Lead', 'id', 'lead_id');
    }

    public function milestones()
    {
        return $this->hasMany('App\Milestone', 'project_id', 'id');
    }

    public function timesheets()
    {
        return $this->hasMany('App\Timesheet', 'project_id', 'id');
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


    public function stages($sort, $dir, $users)
    {
        return Stage::with(['tasks' => function ($query) use ($sort, $dir, $users)
        {
            if(empty($users)) {

                $query->where('project_id', '=', $this->id)
                        ->orderBy($sort?$sort:'priority', $dir?$dir:'asc');
            }else {

                $query->where('project_id', '=', $this->id)
                        ->whereHas('users', function ($query) use($users)
                        {
                            $query->whereIn('users.id', $users);

                        })->orderBy($sort?$sort:'order', $dir?$dir:'asc');
            }

        }], 'tasks.users')
        ->where('class', Task::class)
        ->where('created_by', \Auth::user()->creatorId())
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
            $active  = Project::where('archived', '=', false)->where('client_id', '=', \Auth::user()->client_id)->count();
            $completed = Project::where('archived', '=', true)->where('client_id', '=', \Auth::user()->client_id)->count();
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

    public static function createProject($post)
    {
        if(isset($post['client_id']) && !is_numeric($post['client_id'])) {

            if(!\Auth::user()->checkClientLimit()) return null;

            //new client
            $client = Client::create(['name' => $post['client_id']]);
            $post['client_id'] = $client->id;
        }

        if(isset($post['lead_id']) && !is_numeric($post['lead_id'])) {

            //new lead
            $lead = Lead::create(['name' => $post['lead_id'],
                                    'stage_id' => \Auth::user()->getFirstLeadStage()->id,
                                    'client_id' => $post['client_id']]);
            $post['lead_id'] = $lead->id;
        }

        $project              = Project::make($post);
        $project->save();

        if(isset($post['users']))
        {
            $users = $post['users'];
        }else{

            $users = collect();
        }

        if(\Auth::user()->type != 'company')
        {
            $users->prepend(\Auth::user()->id);
        }

        $project->users()->sync($users);

        Activity::createProject($project);

        return $project;
    }

    public function updateProject($post)
    {
        if(isset($post['client_id']) && !is_numeric($post['client_id'])) {

            //new client
            $client = Client::create(['name' => $post['client_id']]);
            $post['client_id'] = $client->id;
        }

        if(isset($post['lead_id']) && !is_numeric($post['lead_id'])) {

            //new lead
            $lead = Lead::create(['name' => $post['lead_id'],
                                    'stage_id' => \Auth::user()->getFirstLeadStage()->id,
                                    'client_id' => $post['client_id']]);
            $post['lead_id'] = $lead->id;
        }        

        $this->update($post);

        if(isset($post['users']))
        {
            $users = $post['users'];
        }else{

            $users = collect();
        }

        if(\Auth::user()->type != 'company' &&
            empty($users))
        {
            $users->prepend(\Auth::user()->id);
        }

        $this->users()->sync($users);

        Activity::updateProject($this);
    }

    static function humanFileSize($bytes, $decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    static function translateStatus($status)
    {
        switch($status)
        {
            case 1: return __('archived');
            default: return __('active');
        }
    }

    static function translatePriority($priority)
    {
        switch($priority)
        {
            case 2: return __('low');
            case 1: return __('medium');
            default: return __('high');
        }
    }

    public static $status = [
        'active',
        'archived'
    ];

    public static $permission = [
        // '',
        'show activity',
        'show milestone',
        'create milestone',
        'edit milestone',
        'delete milestone',
        'create task',
        'edit task',
        'delete task',
        'show uploading',
        'view timesheet',
        'create timesheet',
        'edit timesheet',
        'delete timesheet'
    ];



}
