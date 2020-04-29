<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use App\Traits\Invoiceable;
use App\Traits\Checklistable;
use App\Traits\Commentable;
use App\Traits\Taggable;

class Task extends Model implements HasMedia
{
    use NullableFields, HasMediaTrait, Invoiceable, Checklistable, Commentable, Taggable;

    protected $fillable = [
        'title',
        'priority',
        'description',
        'start_date',
        'due_date',
        'project_id',
        'milestone_id',
        'order',
        'stage_id',
    ];

    protected $nullable = [
        'project_id',
        'milestone_id',
        'description',
        'start_date',
        'due_date'
	];

    public static $SEED_PROJECT = 50;
    public static $SEED_FREE = 10;

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function stage()
    {
        return $this->belongsTo('App\TaskStage');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_tasks');
    }

    public function milestone()
    {
        return $this->hasOne('App\Milestone','id','milestone_id');
    }

    public function timesheets()
    {
        return $this->hasMany('App\Timesheet', 'task_id', 'id');
    }

    public function getStatus()
    {
        return TaskStage::find($this->stage_id)->name;
    }

    public static function createTask($post)
    {
        $stage = TaskStage::where('created_by', '=', \Auth::user()->creatorId())->first();

        $post['stage_id']   = $stage->id;

        $task               = Task::make($post);
        $task->created_by  = \Auth::user()->creatorId();
        $task->order = $stage->tasks->count();
        $task->save();

        if(isset($post['users'])){

            $users = $post['users'];
        }else{

            $users = [];
        }

        if(\Auth::user()->type != 'company' && empty($users)){

            $users[] = \Auth::user()->id;
        }

        $task->users()->sync($users);
        $task->syncTags(isset($post['tags'])?$post['tags']:[]);

        Activity::createTask($task);

        return $task;
    }

    public function updateTask($post, $patch)
    {
        $this->update($post);

        if(!$patch) {

            if(isset($post['users']))
            {
                $users = $post['users'];
            }else{

                $users = [];
            }

            if(\Auth::user()->type != 'company' && empty($users)){

                $users[] = \Auth::user()->id;
            }

            $this->users()->sync($users);
            $this->syncTags(isset($post['tags'])?$post['tags']:[]);
        }

        Activity::updateTask($this);
    }

    public function updateOrder($stage, $order)
    {
        $updated = ($this->order != $order || $this->stage_id != $stage);

        if($updated)
        {
            $this->order = $order;
            $this->stage_id = $stage;
            $this->save();

            Activity::updateTask($this);
        }

        return $updated;
    }

    public function detachTask()
    {
        $this->users()->detach();
        $this->tags()->detach();

        $this->comments()->delete();
        $this->checklist()->delete();
    }

}
