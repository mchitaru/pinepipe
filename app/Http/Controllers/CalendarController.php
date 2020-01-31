<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {

        $top_tasks = \Auth::user()->project_due_task();
        $due_tasks = array();
        foreach($top_tasks as $task)
        {
            $due_task['title']  = $task->title;
            $due_task['start']  = $task->due_date;
            $due_task['url'] = route('tasks.show', $task->id);
            $due_tasks[]     = $due_task;
        }

        $due_tasks = str_replace(']"', ']', str_replace('"[', "[", json_encode($due_tasks)));

        return view('calendar.index', compact('due_tasks'));
    }
    public function store(Request $request){
        dd('dsd');
    }
}
