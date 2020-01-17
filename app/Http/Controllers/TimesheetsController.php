<?php

namespace App\Http\Controllers;

use App\Timesheet;
use Illuminate\Http\Request;

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
    public function create()
    {
        // if(\Auth::user()->can('create timesheet'))
        // {

        //     $tasks = Task::where('project_id', '=', $project_id)->get()->pluck('title', 'id');

        //     return view('projects.timesheetCreate', compact('tasks'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if(\Auth::user()->can('create timesheet'))
        // {
        //     $timeSheet             = new Timesheet();
        //     $timeSheet->project_id = $project_id;
        //     $timeSheet->task_id    = $request->task_id;
        //     $timeSheet->date       = $request->date;
        //     $timeSheet->hours      = $request->hours;
        //     $timeSheet->remark     = $request->remark;
        //     $timeSheet->user_id    = \Auth::user()->id;
        //     $timeSheet->save();

        //     return redirect()->route('task.timesheetRecord', $project_id)->with('success', __('Task timesheet successfully created.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
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
    public function edit(Timesheet $timesheet)
    {
        // if(\Auth::user()->can('edit timesheet'))
        // {

        //     $timeSheet = Timesheet::find($timeSheet_id);
        //     $tasks     = Task::where('project_id', '=', $project_id)->get()->pluck('title', 'id');

        //     return view('projects.timesheetEdit', compact('tasks', 'timeSheet', 'project_id'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timesheet $timesheet)
    {
        // if(\Auth::user()->can('edit timesheet'))
        // {

        //     $timeSheet          = Timesheet::find($timeSheet_id);
        //     $timeSheet->task_id = $request->task_id;
        //     $timeSheet->date    = $request->date;
        //     $timeSheet->hours   = $request->hours;
        //     $timeSheet->remark  = $request->remark;
        //     $timeSheet->save();

        //     return redirect()->route('projects.show', $project_id)->with('success', __('Task timesheet successfully updated.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timesheet $timesheet)
    {
        // if(\Auth::user()->can('delete timesheet'))
        // {
        //     $timeSheet = Timesheet::find($timeSheet_id);
        //     $timeSheet->delete();

        //     return redirect()->route('task.timesheetRecord', $project_id)->with('success', __('Task timesheet successfully deleted.'));
        // }
        // else
        // {
        //     return redirect()->back()->with('error', 'Permission denied.');
        // }
    }
}
