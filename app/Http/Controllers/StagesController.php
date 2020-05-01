<?php

namespace App\Http\Controllers;

use App\Stage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::user()->can('manage project stage'))
        {
            $taskStages = Stage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order')->get();

            return view('taskstages.index', compact('taskStages'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->can('create project stage'))
        {
            return view('taskstages.create');
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(\Auth::user()->can('create project stage'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('taskstages.index')->with('error', $messages->first());
            }
            $all_stage         = Stage::where('created_by', \Auth::user()->creatorId())->orderBy('id', 'DESC')->first();
            $stage             = new Stage();
            $stage->name       = $request->name;
            $stage->color      = $request->color;
            $stage->created_by = \Auth::user()->creatorId();
            $stage->order      = (!empty($all_stage) ? ($all_stage->order + 1) : 0);

            $stage->save();

            return redirect()->route('taskstages.index')->with('success', __('Project stage successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function show(Stage $stage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function edit(Stage $stage)
    {
        if(\Auth::user()->can('edit project stage'))
        {
            $leadstages = Stage::findOrfail($id);
            if($leadstages->created_by == \Auth::user()->creatorId())
            {
                return view('taskstages.edit', compact('leadstages'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stage $stage)
    {
        if(\Auth::user()->can('edit project stage'))
        {
            $leadstages = Stage::findOrfail($id);
            if($leadstages->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('taskstages.index')->with('error', $messages->first());
                }

                $leadstages->name  = $request->name;
                $leadstages->color = $request->color;
                $leadstages->save();

                return redirect()->route('taskstages.index')->with('success', __('Project stage successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stage $stage)
    {
        if(\Auth::user()->can('delete project stage'))
        {
            $taskStages = Stage::findOrfail($id);
            if($taskStages->created_by == \Auth::user()->creatorId())
            {
                $checkStage = Task::where('stage', '=', $taskStages->id)->get()->toArray();
                if(empty($checkStage))
                {
                    $taskStages->delete();

                    return redirect()->route('taskstages.index')->with('success', __('Project stage successfully deleted.'));
                }
                else
                {
                    return redirect()->route('taskstages.index')->with('error', __('Project task already assign this stage , so please remove or move task to other project stage.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
