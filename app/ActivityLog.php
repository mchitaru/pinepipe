<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'project_id','log_type','remark'
    ];

    public static function createTask(Task $task)
    {
        ActivityLog::create(
            [
                'user_id' => \Auth::user()->creatorId(),
                'project_id' => $task->project_id,
                'log_type' => 'Create Task',
                'remark' => \Auth::user()->name . ' ' . __('Create new Task') . " <b>" . $task->title . "</b>",
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('create task') .
                            ' <a href="' . route('tasks.show', $task->id) . '" data-remote="true" data-type="text">'. $task->title.'</a>',
            ]
        );
    }
}
