<?php namespace App\Http\Traits;

use App\Task;
use App\Project;
use App\TaskChecklist;

trait TaskTraits
{
    public function taskShow(Task $task)
    {
        if(\Auth::user()->can('show task'))
        {
            clock()->startEvent('TaskTraits.show', "Load task");

            $project = Project::find($task->project_id);

            $subtasks = $task->subtasks()->orderBy('order')->get();

            clock()->endEvent('TaskTraits.show');

            return view('tasks.show', compact('task', 'subtasks', 'project'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}