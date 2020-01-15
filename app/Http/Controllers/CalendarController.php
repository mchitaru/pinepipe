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
            $due_task['title'] = $task['name'] . ' / ' . $task['title'];
            $due_task['start'] = $task['task_due_date'];
            if($task['priority'] == 'high')
            {
                $due_task['backgroundColor'] = '#f1b8bc';
            }
            elseif($task['priority'] == 'medium')
            {
                $due_task['backgroundColor'] = '#e8d99a';
            }
            else
            {
                $due_task['backgroundColor'] = '#b6e6ea';
            }
            $due_task['url'] = route('task.show', $task['task_id']);
            $due_tasks[]     = $due_task;
        }
        $due_tasks = str_replace(']"', ']', str_replace('"[', "[", json_encode($due_tasks)));

        return view('calendar.index', compact('due_tasks'));
    }
    public function store(Request $request){
        dd('dsd');
    }
}
