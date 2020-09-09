<?php

namespace App\Http\Controllers;

use App\Event;
use App\Category;
use App\User;
use App\Lead;
use Illuminate\Http\Request;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Requests\EventDestroyRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

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
        $user = \Auth::user();

        $start = $request->start?$request->start:Carbon::now()->roundUnit('minute', 15, 'ceil');
        $end = $request->end?$request->end:Carbon::now()->roundUnit('minute', 15, 'ceil');

        $users  = User::where('created_by', '=', \Auth::user()->created_by)
                        ->where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend(__('(myself)'), \Auth::user()->id);

        if($user->type == 'company')
        {
            $leads = Lead::where('created_by', '=', $user->created_by)
                    ->orderBy('order')
                    ->get()
                    ->pluck('name', 'id');

        }else
        {
            $leads = $user->leads()
                        ->where('created_by', '=', $user->created_by)
                        ->orderBy('order')
                        ->get()
                        ->pluck('name', 'id');
        }

        $lead_id = null;
        if(isset($request['lead_id']))
            $lead_id = $request['lead_id'];

        return view('events.create', compact('users', 'leads', 'start', 'end', 'lead_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventStoreRequest $request)
    {
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $user = \Auth::user();

        $users  = User::where('created_by', '=', \Auth::user()->created_by)
                        ->where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend(__('(myself)'), \Auth::user()->id);

        if($user->type == 'company')
        {
            $leads = Lead::where('created_by', '=', $user->created_by)
                    ->orderBy('order')
                    ->get()
                    ->pluck('name', 'id');

        }else
        {
            $leads = $user->leads()
                        ->where('created_by', '=', $user->created_by)
                        ->orderBy('order')
                        ->get()
                        ->pluck('name', 'id');
        }

        $lead = $event->leads->first();
        $lead_id = $lead?$lead->id:null;

        $user_id = $event->users()->get()->pluck('id');

        return view('events.edit', compact('event', 'users', 'user_id', 'leads', 'lead_id'));
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
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $event->delete();

        return Redirect::to(URL::previous())->with('success', __('Event successfully deleted.'));
    }
}
