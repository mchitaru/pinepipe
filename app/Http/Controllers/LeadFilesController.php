<?php

namespace App\Http\Controllers;

use App\Lead;
use App\Media;
use App\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $request->validate(['file' => 'required|mimetypes:image/*,text/*,font/*,application/*|max:10240']);

        if($request->hasFile('file'))
        {
            $file = $lead->addMedia($request->file('file'))->toMediaCollection('leads', 's3');
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
        return Storage::disk('s3')->download($file->getPath());
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
