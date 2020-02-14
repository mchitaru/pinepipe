<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectFile;
use App\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Helpers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ProjectFilesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {        
        $request->validate(['file' => 'required|mimes:jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,zip,rar|max:2048']);

        if($request->hasFile('file'))
        {
            $path = Helpers::storePrivateFile($request->file('file'));

            $file                 = ProjectFile::create(
                [
                    'project_id' => $project->id,
                    'file_name' => $request->file('file')->getClientOriginalName(),
                    'file_path' => $path,
                    'created_by'=> \Auth::user()->id
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

        ActivityLog::createProjectFile($file);

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
     * @param  \App\ProjectFile  $projectFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Project $project, ProjectFile $file)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        $path = storage_path('app/' . $file->file_path);
        if(file_exists($path))
        {
            \File::delete($path);
        }
        $file->delete();

        return Redirect::to(URL::previous() . "#files")->with('success', __('File successfully deleted'));
    }
}
