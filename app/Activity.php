<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'user_id', 
        'project_id',
        'log_type',
        'remark'
    ];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }    

    public static function createTask(Task $task)
    {
        Activity::create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->creatorId(),
                'project_id' => $task->project_id,
                'log_type' => 'task',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('created task') .
                            ' <a href="' . route('tasks.show', $task->id) . '" data-remote="true" data-type="text">'. $task->title.'</a>',
            ]
        );
    }

    public static function updateTask(Task $task)
    {
        Activity::create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->creatorId(),
                'project_id' => $task->project_id,
                'log_type' => 'task',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('updated task') .
                            ' <a href="' . route('tasks.show', $task->id) . '" data-remote="true" data-type="text">'. $task->title.'</a>',
            ]
        );
    }

    public static function deleteTask(Task $task)
    {
        Activity::create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->creatorId(),
                'project_id' => $task->project_id,
                'log_type' => 'task',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('deleted task') .
                            ' <b>'. $task->title.'</b>',
            ]
        );
    }

    public static function createProject(Project $project)
    {
        Activity::create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->creatorId(),
                'project_id' => $project->project_id,
                'log_type' => 'project',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('created project') .
                            ' <a href="' . route('projects.show', $project->id) . '">'. $project->name.'</a>',
            ]
        );
    }

    public static function updateProject(Project $project)
    {
        Activity::create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->creatorId(),
                'project_id' => $project->project_id,
                'log_type' => 'project',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('updated project') .
                            ' <a href="' . route('projects.show', $project->id) . '">'. $project->name.'</a>',
            ]
        );
    }

    public static function deleteProject(Project $project)
    {
        Activity::create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->creatorId(),
                'project_id' => $project->project_id,
                'log_type' => 'project',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('deleted project') .
                            ' <b>'. $project->name.'</b>',
            ]
        );
    }

    public static function createProjectFile(ProjectFile $file)
    {
        Activity::create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->creatorId(),
                'project_id' => $file->project->id,
                'log_type' => 'project',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('uploaded file') .
                            ' <a href="' . route('projects.file.download', [$file->project->id, $file->id]) . '">'. $file->file_name.'</a>',
            ]
        );
    }

    public static function createTaskFile(TaskFile $file)
    {
        Activity::create(
            [
                'user_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->creatorId(),
                'project_id' => $file->task->project_id,
                'log_type' => 'task',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('uploaded file') .
                            ' <a href="' . route('tasks.file.download', [$file->task->id, $file->id]) . '">'. $file->file_name.'</a>',
            ]
        );
    }

}
