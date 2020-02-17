<?php

namespace App\Http\Controllers;

use App\Milestone;
use Illuminate\Http\Request;

class ProjectMilestonesController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $project = Project::find($project_id);
        // $status  = Project::$status;

        // return view('projects.milestone.create', compact('project', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $project = Project::find($project_id);
        // $request->validate(
        //     [
        //         'title' => 'required',
        //         'status' => 'required',
        //         'cost' => 'required',
        //     ]
        // );

        // $milestone              = new Milestone();
        // $milestone->project_id  = $project->id;
        // $milestone->title       = $request->title;
        // $milestone->status      = $request->status;
        // $milestone->cost        = $request->cost;
        // $milestone->description = $request->description;
        // $milestone->save();

        // Activity::create(
        //     [
        //         'user_id' => \Auth::user()->creatorId(),
        //         'project_id' => $project->id,
        //         'log_type' => 'Create Milestone',
        //         'remark' => '<b>'. \Auth::user()->name . '</b> ' .
        //                     __('created milestone') .
        //                     ' <a href="' . route('project.milestone.show', $milestone->id) . '">'. $milestone->title.'</a>',
        //     ]
        // );

        // return redirect()->back()->with('success', __('Milestone successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function show(Milestone $milestone)
    {
        // $milestone = Milestone::find($id);

        // return view('projects.milestoneShow', compact('milestone'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function edit(Milestone $milestone)
    {
        // $milestone = Milestone::find($id);
        // $status    = Project::$status;

        // return view('projects.milestoneEdit', compact('milestone', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Milestone $milestone)
    {
        // $request->validate(
        //     [
        //         'title' => 'required',
        //         'status' => 'required',
        //         'cost' => 'required',
        //     ]
        // );

        // $milestone              = Milestone::find($id);
        // $milestone->title       = $request->title;
        // $milestone->status      = $request->status;
        // $milestone->cost        = $request->cost;
        // $milestone->description = $request->description;
        // $milestone->save();

        // return redirect()->back()->with('success', __('Milestone updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Milestone  $milestone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Milestone $milestone)
    {
        // $milestone = Milestone::find($id);
        // $milestone->delete();

        // return redirect()->back()->with('success', __('Milestone successfully deleted.'));
    }
}
