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
            $event['start']  = $ev->start;
            $event['end']  = $ev->end;
            $event['url'] = route('events.edit', $ev->id);
            $event['backgroundColor'] = '#007bff';
            $events[]     = $event;
        }

        //add tasks
        foreach(\Auth::user()->getTasksByUserType() as $t)
        {
            $task['title']  = $t->title;
            $task['start']  = $t->due_date;
            $task['url'] = route('tasks.show', $t->id);
            $task['backgroundColor'] = '#20c997';
            $events[]     = $task;
        }

        $events = str_replace(']"', ']', str_replace('"[', "[", json_encode($events)));

        return view('calendar.index', compact('events'));
    }
    public function store(Request $request){
        dd('dsd');
    }
}
