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

            $projects = $user->getProjectsByUserType();
            
            $project_status = Project::$project_status;

            $stages  = $user->getProjectStages();            
            $task_count = $user->getTasksByUserType()->count();

            $project_id = null;
            $activities = array();

            return view('sections.projects.index', compact('projects', 'project_status', 'project_id', 'stages', 'task_count', 'activities'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
