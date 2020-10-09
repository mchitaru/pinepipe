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
use Illuminate\Support\Facades\Gate;

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
        Gate::authorize('create', 'App\Timesheet');

        $project_id = $request['project_id'];

        $date = $request->date ? $request->date : date('Y-m-d');

        $projects   = \Auth::user()->projects()->get()->pluck('name', 'id');

        $tasks = Task::where('project_id', '=', $project_id)->get()->pluck('title', 'id');

        return view('timesheets.create', compact('projects', 'project_id', 'tasks', 'date'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TimesheetStoreRequest $request)
    {
        Gate::authorize('create', 'App\Timesheet');

        $post = $request->validated();

        Timesheet::createTimesheet($post);

        $request->session()->flash('success', __('Timesheet successfully created.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function show(Timesheet $timesheet)
    {
        Gate::authorize('view', $timesheet);

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Timesheet $timesheet)
    {
        Gate::authorize('update', $timesheet);

        $date = $request->date;

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

        return view('timesheets.edit', compact('projects', 'tasks', 'timesheet', 'project_id', 'date'));
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
        Gate::authorize('update', $timesheet);

        $post = $request->validated();

        $timesheet->updateTimesheet($post);

        $request->session()->flash('success', __('Timesheet successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimesheetDestroyRequest $request, Timesheet $timesheet)
    {
        Gate::authorize('delete', $timesheet);

        if($request->ajax()){

            return view('helpers.destroy');
        }

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

            return $this->edit($request, $timesheet);
        }

        return $this->create($request);
    }

    public function timer(Request $request)
    {        
        $timesheet = null;
        $start = false;
        $offset = 0;

        $task = Task::find($request['task_id']);

        if($task) {

            $timesheet = \Auth::user()->timesheets()->where('task_id', $task->id)
                                                    ->where(function ($query)  {
                                                        $query->where('started_at','!=', null)
                                                                ->orWhereDate('date', '=', Carbon::now());
                                                                
                                                    })                                                    
                                                    ->orderBy('updated_at', 'desc')
                                                    ->first();

        }else{

            $timesheet = Timesheet::find($request['timesheet_id']);
        }

        if(is_null($timesheet)) {

            $post['date'] = date('Y-m-d');
            $post['hours'] = 0;
            $post['minutes'] = 0;
            $post['seconds'] = 0;
            $post['rate'] = 0;

            if($task) {

                $post['project_id'] = $task->project_id;
                $post['task_id'] = $task->id;
            }

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

        //reload the relationship to reflect the changes
        \Auth::user()->load('timesheets');

        $popup = view('partials.app.timesheets')->render();
        $control = view('partials.app.timesheetctrl', compact('task', 'timesheet'))->render();

        return response()->json(['start' => $start,
                                    'url' => ($task == null) ? route('timesheets.edit', $timesheet->id) : null,
                                    'offset' => $offset,
                                    'task_id' => $timesheet->task_id,
                                    'timesheet_id' => $timesheet->id,
                                    'popup' => $popup,
                                    'control' => $control]);
    }
}
