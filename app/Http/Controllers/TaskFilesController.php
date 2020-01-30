<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskFile;
use Illuminate\Http\Request;
use App\Http\Helpers;

class TaskFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Task $task)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $task    = Task::find($task_id);
        // $project = Project::find($task->project_id);

        // $request->validate(
        //     ['file' => 'required|mimes:jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,zip,rar|max:2048']
        // );

        // if($request->hasFile('file'))
        // {
        //     $path = Helpers::storePrivateFile($request->file('file'));

        //     $post['task_id']    = $task_id;
        //     $post['file']       = $path;
        //     $post['name']       = $request->file->getClientOriginalName();
        //     $post['extension']  = "." . $request->file->getClientOriginalExtension();
        //     $post['file_size']  = round(($request->file->getSize() / 1024) / 1024, 2) . ' MB';
        //     $post['created_by'] = \Auth::user()->authId();
        //     $post['user_type']  = \Auth::user()->type;

        //     $file            = TaskFile::create($post);
        //     $file->deleteUrl = route('task.file.delete', [$task_id, $file->id]);
        // }

        // $return               = [];
        // $return['is_success'] = true;
        // $return['download']   = route(
        //     'task.file.download', [
        //                                 $task_id,
        //                                 $file->id,
        //                             ]
        // );
        // $return['delete']     = route(
        //     'task.file.delete', [
        //                               $task_id,
        //                               $file->id,
        //                           ]
        // );

        // ActivityLog::create(
        //     [
        //         'user_id' => \Auth::user()->creatorId(),
        //         'project_id' => $project->id,
        //         'log_type' => 'Upload File',
        //         'remark' => '<b>'. \Auth::user()->name . '</b> ' .
        //                     __('uploaded file') .
        //                     ' <a href="' . route('task.file.download', [$task_id, $file->id]) . '">'. $request->file->getClientOriginalName().'</a>',
        //     ]
        // );

        // return response()->json($return);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TaskFile  $taskFile
     * @return \Illuminate\Http\Response
     */
    public function show(TaskFile $taskFile)
    {
        // $file    = TaskFile::find($file_id);
        // if($file)
        // {
        //     $file_path = storage_path('app/public/tasks/' . $file->file);
        //     $filename  = $file->name;

        //     return \Response::download(
        //         $file_path, $filename, [
        //                       'Content-Length: ' . filesize($file_path),
        //                   ]
        //     );
        // }
        // else
        // {
        //     return redirect()->back()->with('error', __('File does not exist.'));
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskFile  $taskFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskFile $taskFile)
    {
        // $file = TaskFile::find($file_id);
        // if($file)
        // {
        //     $path = storage_path('app/public/tasks/' . $file->file);
        //     if(file_exists($path))
        //     {
        //         \File::delete($path);
        //     }
        //     $file->delete();

        //     return response()->json(['is_success' => true], 200);
        // }
        // else
        // {
        //     return response()->json(
        //         [
        //             'is_success' => false,
        //             'error' => __('File is not exist.'),
        //         ], 200
        //     );
        // }
    }
}
