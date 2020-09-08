<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $events = array();

        //add events
        foreach(\Auth::user()->events as $ev)
        {
            $event['title']  = $ev->name;
            $event['start']  = \Helpers::utcToLocal($ev->start);
            $event['end']  = \Helpers::utcToLocal($ev->end);
            $event['allDay']  = $ev->allDay;
            $event['url'] = route('events.edit', $ev->id);
            $event['color'] = $ev->calendar?$ev->calendar->color:'#6fb1f7';
            $events[]     = $event;
        }

        //add tasks
        $lastTaskStageId = \Auth::user()->getLastTaskStage()->id;
        $tasks = \Auth::user()->tasksByUserType()
                                ->where('stage_id', '<', $lastTaskStageId)
                                ->get();
        foreach($tasks as $t)
        {
            $task['title']  = $t->title;
            $task['start']  = $t->due_date;
            $task['allDay']  = true;
            $task['url'] = route('tasks.show', $t->id);
            $task['color'] = '#82d8be';
            $events[]     = $task;
        }

        $events = str_replace(']"', ']', str_replace('"[', "[", json_encode($events)));

        return view('calendar.index', compact('events'));
    }
    public function store(Request $request){
    }
}
