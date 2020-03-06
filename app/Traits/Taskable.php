<?php namespace App\Traits;

use App\Task;
use App\Project;
use App\TaskChecklist;

trait Taskable
{
    public function taskShow(Task $task)
    {
        if(\Auth::user()->can('show task'))
        {
            clock()->startEvent('Taskable.show', "Load task");

            $project = Project::find($task->project_id);

            $subtasks = $task->subtasks()->orderBy('order')->get();

            $files = [];
            foreach($task->getMedia('tasks') as $media)
            {
                $file = [];
                
                $file['file_name'] = $media->file_name;
                $file['size'] = $media->size;
                $file['download'] = route('tasks.file.download',[$task->id, $media->id]);
                $file['delete'] = route('tasks.file.delete', [$task->id, $media->id]);

                $files[] = $file;
            }

            clock()->endEvent('Taskable.show');

            return view('tasks.show', compact('task', 'subtasks', 'project', 'files'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}