<?php

namespace App\Http\Controllers;

use App\Project;
use App\Media;
use App\Activity;
use Illuminate\Http\Request;

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
            $file = $project->addMedia($request->file('file'))->toMediaCollection('projects', 'local');
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

        Activity::createProjectFile($project, $file);

        return response()->json($return);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Media  $file
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Media $file)
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
    public function destroy(Request $request, Project $project, Media $file)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        $path = $file->getPath();
        if(file_exists($path))
        {
            \File::delete($path);
        }
        $file->delete();

        return Redirect::to(URL::previous() . "#project-files")->with('success', __('File successfully deleted'));
    }
}
