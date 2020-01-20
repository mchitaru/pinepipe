<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use App\Milestone;
use App\UserProject;
use App\ActivityLog;
use App\ProjectStage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProjectTasksController extends Controller
{
    public function board($project_id)
    {
        if(\Auth::user()->can('show project'))
        {
            $stages  = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order', 'ASC')->get();

            return view('tasks.board', compact('stages', 'project_id'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        $project    = Project::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $project_id)->first();
        $projects   = Project::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $milestones = Milestone::where('project_id', '=', $project->id)->get()->pluck('title', 'id');
        $priority   = Project::$priority;
        $usersArr   = UserProject::where('project_id', '=', $project->id)->get();
        $users      = array();
        foreach($usersArr as $user)
        {
            $users[$user->project_assign_user->id] = ($user->project_assign_user->name . ' - ' . $user->project_assign_user->email);
        }
        $project_id = $project->id;

        return view('tasks.create', compact('project', 'projects', 'priority', 'users', 'milestones', 'project_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id)
    {
        if(\Auth::user()->type == 'company')
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'priority' => 'required',
                                   'assign_to' => 'required',
                                   'due_date' => 'required',
                                   'start_date' => 'required',
                               ]
            );
        }
        else
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                                   'priority' => 'required',
                                   'due_date' => 'required',
                                   'start_date' => 'required',
                               ]
            );
        }
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $project = Project::where('created_by', '=', \Auth::user()->creatorId())->where('projects.id', '=', $project_id)->first();
        if($project)
        {
            $post = $request->all();
            if(\Auth::user()->type != 'company')
            {
                $post['assign_to'] = \Auth::user()->id;
            }
            $post['project_id'] = $project_id;
            $post['stage']      = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->first()->id;
            $task               = Task::make($post);
            $task->created_by  = \Auth::user()->creatorId();
            $task->save();
    
            ActivityLog::create(
                [
                    'user_id' => \Auth::user()->creatorId(),
                    'project_id' => $project_id,
                    'log_type' => 'Create Task',
                    'remark' => \Auth::user()->name . ' ' . __('Create new Task') . " <b>" . $task->title . "</b>",
                    'remark' => '<b>'. \Auth::user()->name . '</b> ' .
                                __('created task') .
                                ' <a href="#" data-url="'.route('tasks.show',$task->id).'" data-ajax-popup="true"  data-size="lg">'.$task->title.'</a>',
                ]
            );

            return redirect()->back()->with('success', __('Task successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('You can \'t Add Task.'));
        }
    }
}
