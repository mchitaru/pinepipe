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
            if($user->type == 'client')
            {
                $projects = Project::where('client', '=', $user->id)->get();
            }
            else
            {
                $projects = $user->projects;

            }

            $stages  = ProjectStage::where('created_by', '=', \Auth::user()->creatorId())->orderBy('order', 'ASC')->get();
            $project_status = Project::$project_status;
            $project_id = '';
            
            $activities = array();

            return view('sections.projects.index', compact('projects', 'project_status', 'project_id', 'stages', 'activities'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
