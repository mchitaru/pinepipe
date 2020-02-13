<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use App\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class TimesheetsController extends Controller
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
    public function create(Project $project)
    {
        if(\Auth::user()->can('create timesheet'))
        {
            $project_id = $project->id;
            $tasks = Task::where('project_id', '=', $project->id)->get()->pluck('title', 'id');

            return view('timesheets.create', compact('project_id', 'tasks'));
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
    public function store(Request $request, Project $project)
    {
        if(\Auth::user()->can('create timesheet'))
        {
            $timeSheet             = new Timesheet();
            $timeSheet->project_id = $project->id;
            $timeSheet->task_id    = $request->task_id;
            $timeSheet->date       = $request->date;
            $timeSheet->hours      = $request->hours;
            $timeSheet->rate       = $request->rate;
            $timeSheet->remark     = $request->remark;
            $timeSheet->user_id    = \Auth::user()->id;
            $timeSheet->save();

            return Redirect::to(URL::previous() . "#timesheets")->with('success', __('Timesheet successfully created.'));
        }
        else
        {
            return Redirect::to(URL::previous() . "#timesheets")->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function show(Timesheet $timesheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Timesheet $timesheet)
    {
        if(\Auth::user()->can('edit timesheet'))
        {
            $project_id = $project->id;
            $tasks     = Task::where('project_id', '=', $project->id)->get()->pluck('title', 'id');

            return view('timesheets.edit', compact('tasks', 'timesheet', 'project_id'));
        }
        else
        {
            return Redirect::to(URL::previous() . "#timesheets")->with('error', __('Permission denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, Timesheet $timesheet)
    {
        if(\Auth::user()->can('edit timesheet'))
        {

            $timesheet->task_id = $request->task_id;
            $timesheet->date    = $request->date;
            $timesheet->hours   = $request->hours;
            $timesheet->rate    = $request->rate;
            $timesheet->remark  = $request->remark;
            $timesheet->save();

            return Redirect::to(URL::previous() . "#timesheets")->with('success', __('Timesheet successfully updated.'));
        }
        else
        {
            return Redirect::to(URL::previous() . "#timesheets")->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Project $project, Timesheet $timesheet)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        if(\Auth::user()->can('delete timesheet'))
        {
            $timesheet->delete();

            return Redirect::to(URL::previous() . "#timesheets")->with('success', __('Timesheet successfully deleted.'));
        }
        else
        {
            return Redirect::to(URL::previous() . "#timesheets")->with('error', __('Permission denied.'));
        }
    }
}
