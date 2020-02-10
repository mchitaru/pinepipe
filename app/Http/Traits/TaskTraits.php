<?php namespace App\Http\Traits;

use App\Task;
use App\Project;

trait TaskTraits
{
    public function taskShow(Task $task)
    {
        if(\Auth::user()->can('show task'))
        {
            $project = Project::find($task->project_id);

            if(!empty($project))
                $permissions = $project->permissions;

            $perArr      = (!empty($permissions) ? explode(',', $permissions->permissions) : []);

            $activities = array();

            return view('tasks.show', compact('task', 'perArr', 'project', 'activities'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}