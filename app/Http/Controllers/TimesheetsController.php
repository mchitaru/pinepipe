<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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

            $projects   = \Auth::user()->projects()->get()->pluck('name', 'id');
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

        return "<script>window.location.reload()</script>";
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
            $project    = $timesheet->project;
            $projects   = \Auth::user()->projects()->get()->pluck('name', 'id');

            if($project)
            {
                $project_id = $project->id;
                $tasks     = Task::where('project_id', '=', $project->id)->get()->pluck('title', 'id');
            }else
            {
                $project_id = null;
                $tasks   = [];
            }

            return view('timesheets.edit', compact('projects', 'tasks', 'timesheet', 'project_id'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
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

        return "<script>window.location.reload()</script>";
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

        return Redirect::to(URL::previous())->with('success', __('Timesheet successfully deleted.'));
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

    public function timer(Request $request)
    {
        $start = false;
        $offset = 0;

        $timesheet = Timesheet::find($request['timesheet_id']);

        if(is_null($timesheet)) {

            $timesheet = \Auth::user()->getActiveTimesheet();
        }

        if(is_null($timesheet)) {

            $post['date'] = date('Y-m-d');
            $post['hours'] = 0;
            $post['minutes'] = 0;
            $post['seconds'] = 0;
            $post['rate'] = 0;

            $timesheet = Timesheet::createTimesheet($post);
        }

        if($timesheet->isStarted()){

            $timesheet->stop();
        }else{

            foreach(\Auth::user()->timesheets as $active)
            {
                //stop other active timesheet before we start another one
                if($active->isStarted()) {

                    $active->stop();
                }
            }

            $timesheet->start();
            $start = true;
        }

        $offset = $timesheet->computeTime();

        $view = view('partials.app.timesheets')->render();

        return response()->json(['start' => $start,
                                    'url' => route('timesheets.edit', $timesheet->id),
                                    'offset' => $offset,
                                    'html' => $view]);
    }
}
