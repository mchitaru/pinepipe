<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use App\Timesheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\TimesheetStoreRequest;
use App\Http\Requests\TimesheetUpdateRequest;
use App\Http\Requests\TimesheetDestroyRequest;

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
    public function create(Request $request)
    {
        if(\Auth::user()->can('create timesheet'))
        {
            $project_id = $request['project_id'];

            $projects   = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $tasks = Task::where('project_id', '=', $project_id)->get()->pluck('title', 'id');

            return view('timesheets.create', compact('projects', 'project_id', 'tasks'));
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
    public function store(TimesheetStoreRequest $request)
    {
        $post = $request->validated();

        Timesheet::createTimesheet($post);

        $request->session()->flash('success', __('Timesheet successfully created.'));

        $url = redirect()->back()->getTargetUrl().'/#timesheets';
        return "<script>window.location='{$url}'</script>";
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
        if(\Auth::user()->can('edit timesheet'))
        {
            $project    = Project::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $timesheet->project_id)->first();
            $projects   = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            if($project)
            {
                $project_id = $project->id;
                $tasks     = Task::where('project_id', '=', $project->id)->get()->pluck('title', 'id');
            }else
            {
                $project_id = null;
                $tasks   = null;
            }            

            return view('timesheets.edit', compact('projects', 'tasks', 'timesheet', 'project_id'));
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
    public function update(TimesheetUpdateRequest $request, Timesheet $timesheet)
    {
        $post = $request->validated();

        $timesheet->updateTimesheet($post);

        $request->session()->flash('success', __('Timesheet successfully updated.'));

        $url = redirect()->back()->getTargetUrl().'/#timesheets';
        return "<script>window.location='{$url}'</script>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimesheetDestroyRequest $request, Timesheet $timesheet)
    {
        if($request->ajax()){
            
            return view('helpers.destroy');
        }

        $timesheet->detachTimesheet();
        $timesheet->delete();

        return Redirect::to(URL::previous() . "#timesheets")->with('success', __('Timesheet successfully deleted.'));
    }

    public function refresh(Request $request, $timesheet_id)
    {
        $request->flash();

        if($timesheet_id)
        {
            $timesheet = Timesheet::find($timesheet_id);
            $timesheet->project_id = $request['project_id'];

            return $this->edit($timesheet);
        }

        return $this->create($request);
    }
}
