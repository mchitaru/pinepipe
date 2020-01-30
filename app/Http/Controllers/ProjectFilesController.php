<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectFile;
use App\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Helpers;

class ProjectFilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {        
        $request->validate(['file' => 'required|mimes:png,jpeg,jpg,pdf,doc,txt|max:2048']);

        if($request->hasFile('file'))
        {
            $path = Helpers::storePrivateFile($request->file('file'));

            $file                 = ProjectFile::create(
                [
                    'project_id' => $project->id,
                    'file_name' => $request->file('file')->getClientOriginalName(),
                    'file_path' => $path,
                ]
            );
        }

        $return               = [];
        $return['is_success'] = true;
        $return['download']   = route(
            'projects.file.download', [
                                        $project->id,
                                        $file->id,
                                    ]
        );
        $return['delete']     = route(
            'projects.file.delete', [
                                      $project->id,
                                      $file->id,
                                  ]
        );

        ActivityLog::create(
            [
                'user_id' => \Auth::user()->creatorId(),
                'project_id' => $project->id,
                'log_type' => 'Upload File',
                'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                            __('uploaded file') .
                            ' <a href="' . route('projects.file.download', [$project->id, $file->id]) . '">'. $file->file_name.'</a>',
            ]
        );

        return response()->json($return);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProjectFile  $projectFile
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, ProjectFile $file)
    {
        if($file)
        {
            $file_path = storage_path('app/' . $file->file_path);
            $filename  = $file->file_name;

            return \Response::download(
                $file_path, $filename, [
                              'Content-Length: ' . filesize($file_path),
                          ]
            );
        }
        else
        {
            return redirect()->back()->with('error', __('File does not exist.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectFile  $projectFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Project $project, ProjectFile $file)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        if($file)
        {
            $path = storage_path('app/' . $file->file_path);
            if(file_exists($path))
            {
                \File::delete($path);
            }
            $file->delete();

            // return response()->json(['is_success' => true], 200);
            return redirect()->route('projects.index')->with('success', __('Expense successfully deleted.'));
        }
        else
        {
            // return response()->json(
            //     [
            //         'is_success' => false,
            //         'error' => __('File is not exist.'),
            //     ], 200
            // );
            return redirect()->back()->with('error', __('File does not exist.'));
        }
    }
}
