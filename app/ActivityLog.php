<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 
        'project_id',
        'log_type',
        'remark'
    ];

    public static function createTask(Task $task)
    {
        ActivityLog::create(
            [
                'user_id' => \Auth::user()->creatorId(),
                'project_id' => $task->project_id,
                'log_type' => 'Create Task',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('created task') .
                            ' <a href="' . route('tasks.show', $task->id) . '" data-remote="true" data-type="text">'. $task->title.'</a>',
            ]
        );
    }

    public static function updateTask(Task $task)
    {
        ActivityLog::create(
            [
                'user_id' => \Auth::user()->creatorId(),
                'project_id' => $task->project_id,
                'log_type' => 'Update Task',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('updated task') .
                            ' <a href="' . route('tasks.show', $task->id) . '" data-remote="true" data-type="text">'. $task->title.'</a>',
            ]
        );
    }

    public static function deleteTask(Task $task)
    {
        ActivityLog::create(
            [
                'user_id' => \Auth::user()->creatorId(),
                'project_id' => $task->project_id,
                'log_type' => 'Delete Task',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('deleted task') .
                            ' <b>'. $task->title.'</b>',
            ]
        );
    }

    public static function createProject(Project $project)
    {
        ActivityLog::create(
            [
                'user_id' => \Auth::user()->creatorId(),
                'project_id' => $project->project_id,
                'log_type' => 'Create Project',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('created project') .
                            ' <a href="' . route('projects.show', $project->id) . '">'. $project->title.'</a>',
            ]
        );
    }

    public static function updateProject(Project $project)
    {
        ActivityLog::create(
            [
                'user_id' => \Auth::user()->creatorId(),
                'project_id' => $project->project_id,
                'log_type' => 'Update Project',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('updated task') .
                            ' <a href="' . route('projects.show', $project->id) . '">'. $project->title.'</a>',
            ]
        );
    }

    public static function deleteProject(Project $project)
    {
        ActivityLog::create(
            [
                'user_id' => \Auth::user()->creatorId(),
                'project_id' => $project->project_id,
                'log_type' => 'Delete Task',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('deleted task') .
                            ' <b>'. $project->name.'</b>',
            ]
        );
    }

}
