<?php namespace App\Http\Traits;

use App\Task;
use App\Project;

trait TaskTraits
{
    public function taskShow($task_id)
    {
        $task    = Task::find($task_id);
        $project = Project::find($task->project_id);

        if(!empty($project))
            $permissions = $project->client_project_permission();

        $perArr      = (!empty($permissions) ? explode(',', $permissions->permissions) : []);

        $activities = array();

        return view('tasks.show', compact('task', 'perArr', 'project', 'activities'));
    }
}