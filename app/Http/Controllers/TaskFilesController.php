<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskFile;
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
            $path = \Helpers::storePrivateFile($request->file('file'));

            $file                 = TaskFile::create(
                [
                    'task_id'   => $task->id,
                    'file_name' => $request->file->getClientOriginalName(),
                    'file_path' => $path,
                    'created_by'=> \Auth::user()->id
                ]
            );
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

        Activity::createTaskFile($file);

        return response()->json($return);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TaskFile  $taskFile
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task, TaskFile $file)
    {
        $file_path = storage_path('app/' . $file->file_path);
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
     * @param  \App\TaskFile  $taskFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Task $task, TaskFile $file)
    {
        $path = storage_path('app/' . $file->file_path);
        if(file_exists($path))
        {
            \File::delete($path);
        }
        $file->delete();

        return $this->taskShow($task);
    }
}
