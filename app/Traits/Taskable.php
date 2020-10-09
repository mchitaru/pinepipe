<?php namespace App\Traits;

use App\Task;
use App\Project;
use App\Checklist;
use App\Timesheet;

use Illuminate\Support\Facades\Gate;

trait Taskable
{
    public function taskShow(Task $task)
    {
        Gate::authorize('view', $task);

        clock()->startEvent('Taskable.show', "Load task");

        $project = Project::find($task->project_id);

        $subtasks = $task->checklist()->get();

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

        $timesheet = \Auth::user()->timesheets()->where('task_id', $task->id)->orderBy('updated_at', 'desc')->first();

        clock()->endEvent('Taskable.show');

        return view('tasks.show', compact('task', 'subtasks', 'project', 'files', 'timesheet'));
    }
}