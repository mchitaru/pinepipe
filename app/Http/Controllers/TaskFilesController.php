<?php

namespace App\Http\Controllers;

use App\Task;
use App\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Activity;
use App\Http\Requests\TaskFileRequest;
use App\Traits\Taskable;
use Illuminate\Support\Facades\Gate;

class TaskFilesController extends Controller
{
    use Taskable;

    public function index(Task $task)
    {
        Gate::authorize('view', $task);

        return $this->taskShow($task);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskFileRequest $request, Task $task)
    {
        Gate::authorize('create', ['App\Media', $task]);

        $post = $request->validated();


        if($request->hasFile('file'))
        {
            $file = $task->addMedia($request->file('file'))->toMediaCollection('tasks', 's3');
        }

        $return               = [];
        $return['is_success'] = true;
        $return['download']   = route(
            'tasks.file.download', [
                                        $task->id,
                                        $file->id,
                                    ]
        );
        $return['delete']     = route(
            'tasks.file.delete', [
                                      $task->id,
                                      $file->id,
                                  ]
        );

        Activity::createTaskFile($task, $file);

        return response()->json($return);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Media  $file
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task, Media $file)
    {
        Gate::authorize('view', $file);

        return Storage::disk('s3')->download($file->getPath());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Media  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Task $task, Media $file)
    {
        Gate::authorize('delete', $file);

        $path = $file->getPath();
        if(file_exists($path))
        {
            \File::delete($path);
        }
        $file->delete();

        return $this->taskShow($task);
    }
}
