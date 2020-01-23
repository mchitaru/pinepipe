<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use App\ProjectStage;
use Illuminate\Http\Request;

class ProjectsSectionController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage project'))
        {
            $user = \Auth::user();
            if($user->type == 'client'){

                $projects = Project::where('client', '=', $user->id)->get();
            }
            else if($user->type == 'company'){

                $projects = Project::where('created_by', '=', \Auth::user()->creatorId())->get();
            }else{

                $projects = $user->projects;

            }

            $project_status = Project::$project_status;
            $project_id = '';

            $activities = array();

            $stages  = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order', 'ASC')->get();
            $task_count = 0;
            foreach($stages as $stage){
                $task_count = $task_count + count($stage->tasks($project_id));
            }

            return view('sections.projects.index', compact('projects', 'project_status', 'project_id', 'stages', 'task_count', 'activities'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
