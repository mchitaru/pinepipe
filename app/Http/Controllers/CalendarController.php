<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        // $top_tasks = \Auth::user()->project_due_task();
        // $due_tasks = array();
        // foreach($top_tasks as $task)
        // {
        //     $due_task['title']  = $task->title;
        //     $due_task['start']  = $task->due_date;
        //     $due_task['url'] = route('tasks.show', $task->id);
        //     $due_tasks[]     = $due_task;
        // }

        // $due_tasks = str_replace(']"', ']', str_replace('"[', "[", json_encode($due_tasks)));

        $events = array();
        foreach(\Auth::user()->events as $ev)
        {
            $event['title']  = $ev->name;
            $event['start']  = $ev->start;
            $event['end']  = $ev->end;
            $event['url'] = route('events.edit', $ev->id);
            $events[]     = $event;
        }

        $events = str_replace(']"', ']', str_replace('"[', "[", json_encode($events)));

        return view('calendar.index', compact('events'));
    }
    public function store(Request $request){
        dd('dsd');
    }
}
