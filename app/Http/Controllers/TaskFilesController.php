<?php

namespace App\Http\Controllers;

use App\Task;
use App\Media;
use Illuminate\Http\Request;

use App\Activity;
use App\Http\Requests\TaskFileRequest;
use App\Http\Traits\TaskTraits;

class TaskFilesController extends Controller
{
    use TaskTraits;

    public function index(Task $task)
    {
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
        $post = $request->validated();


        if($request->hasFile('file'))
        {
            $file = $task->addMedia($request->file('file'))->toMediaCollection('tasks', 'local');
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
        $file_path = $file->getPath();
        $filename  = $file->file_name;
        
        return \Response::download(
            $file_path, $filename, [
                            'Content-Length: ' . filesize($file_path),
                        ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Media  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Task $task, Media $file)
    {
        $path = $file->getPath();
        if(file_exists($path))
        {
            \File::delete($path);
        }
        $file->delete();

        return $this->taskShow($task);
    }
}
