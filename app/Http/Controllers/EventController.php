<?php

namespace App\Http\Controllers;

use App\Event;
use App\Category;
use App\User;
use App\Lead;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Requests\EventDestroyRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
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
        Gate::authorize('create', 'App\Event');

        $user = \Auth::user();

        $start = $request->start?$request->start:Carbon::now()->roundUnit('minute', 15, 'ceil');
        $end = $request->end?$request->end:Carbon::now()->roundUnit('minute', 15, 'ceil');

        $lead_id = null;
        if(isset($request['lead_id']))
            $lead_id = $request['lead_id'];

        $project = null;
        $project_id = null;
        if(isset($request['project_id'])){

            $project_id = $request['project_id'];
            $project = Project::find($project_id);
        }

        if($project){

            $users   = $project->users()
                                ->get()
                                ->pluck('name', 'id')
                                ->prepend(__('(me)'), \Auth::user()->id);    
        }else{

            $users  = User::where('type', '!=', 'client')
                            ->get()
                            ->pluck('name', 'id')
                            ->prepend(__('(me)'), \Auth::user()->id);
        }

        $leads = Lead::orderBy('order')
                        ->get()
                        ->pluck('name', 'id');

        $projects = Project::get()
                            ->pluck('name', 'id');

        return view('events.create', compact('users', 'leads', 'projects', 'start', 'end', 'lead_id', 'project_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventStoreRequest $request)
    {
        Gate::authorize('create', 'App\Event');

        $post = $request->validated();

        Event::createEvent($post);

        $request->session()->flash('success', __('Event successfully created.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        Gate::authorize('view', $event);

        $user = \Auth::user();

        $leads = Lead::orderBy('order')
                        ->get()
                        ->pluck('name', 'id');

        $lead = $event->leads->first();
        $lead_id = $lead?$lead->id:null;

        $projects = $user->companyProjects()
                            ->get()
                            ->pluck('name', 'id');

        $project = $event->projects->first();
        $project_id = $project?$project->id:null;

        if($project){

            $users   = $project->users()
                                ->get()
                                ->pluck('name', 'id')
                                ->prepend(__('(me)'), \Auth::user()->id);    
        }else{

            $users  = User::where('type', '!=', 'client')
                            ->get()
                            ->pluck('name', 'id')
                            ->prepend(__('(me)'), \Auth::user()->id);
        }


        $user_id = $event->users()->get()->pluck('id');

        return view('events.show', compact('event', 'users', 'user_id', 'leads', 'lead_id', 'projects', 'project_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Event $event)
    {
        Gate::authorize('update', $event);

        $user = \Auth::user();

        $leads = Lead::orderBy('order')
                        ->get()
                        ->pluck('name', 'id');

        $lead = $event->leads->first();
        $lead_id = $lead?$lead->id:null;

        $projects = Project::get()
                            ->pluck('name', 'id');

        if(isset($request['project_id'])){

            $project_id = $request['project_id'];
            $project = Project::find($project_id);

        }else{

            $project = $event->projects->first();
            $project_id = $project?$project->id:null;
        } 

        if($project){

            $users   = $project->users()
                                ->get()
                                ->pluck('name', 'id')
                                ->prepend(__('(me)'), \Auth::user()->id);    
        }else{

            $users  = User::where('type', '!=', 'client')
                            ->get()
                            ->pluck('name', 'id')
                            ->prepend(__('(me)'), \Auth::user()->id);
        }

        $user_id = $event->users()->get()->pluck('id');

        return view('events.edit', compact('event', 'users', 'user_id', 'leads', 'lead_id', 'projects', 'project_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventUpdateRequest $request, Event $event)
    {
        Gate::authorize('update', $event);

        $post = $request->validated();

        $event->updateEvent($post);

        $request->session()->flash('success', __('Event successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventDestroyRequest $request, Event $event)
    {
        Gate::authorize('delete', $event);

        if($request->ajax()){

            return view('helpers.destroy');
        }

        $event->delete();

        return Redirect::to(URL::previous())->with('success', __('Event successfully deleted.'));
    }

    public function refresh(Request $request, $event_id)
    {
        $request->flash();

        if($event_id)
        {
            $event = Event::find($event_id);

            return $this->edit($request, $event);
        }

        return $this->create($request);
    }
}
