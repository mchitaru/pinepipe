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

            foreach($task->files as $file)
            {
                $file->size = filesize(storage_path('app/'.$file->file_path));
                $file->download = route('tasks.file.download',[$task->id,$file->id]);
                $file->delete = route('tasks.file.delete',[$task->id,$file->id]);
            }


            clock()->endEvent('TaskTraits.show');

            return view('tasks.show', compact('task', 'subtasks', 'project'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}