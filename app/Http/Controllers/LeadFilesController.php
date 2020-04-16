<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Media;
use App\Activity;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class LeadFilesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Lead $lead)
    {        
        $request->validate(['file' => 'required|mimes:jpeg,jpg,png,gif,svg,pdf,txt,doc,docx,zip,rar|max:2048']);

        if($request->hasFile('file'))
        {
            $file = $lead->addMedia($request->file('file'))->toMediaCollection('leads', 'local');
        }

        $return               = [];
        $return['is_success'] = true;
        $return['download']   = route(
            'leads.file.download', [
                                        $lead->id,
                                        $file->id,
                                    ]
        );
        $return['delete']     = route(
            'leads.file.delete', [
                                        $lead->id,
                                        $file->id,
                                  ]
        );

        Activity::createLeadFile($lead, $file);

        return response()->json($return);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Media  $file
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead, Media $file)
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
    public function destroy(Request $request, Lead $lead, Media $file)
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

        return Redirect::to(URL::previous())->with('success', __('File successfully deleted'));
    }
}
