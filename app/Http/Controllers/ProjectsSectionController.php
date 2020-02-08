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
            
            $stages = ProjectStage::with('tasks.checklist')
                                    ->where('created_by', '=', \Auth::user()->creatorId())
                                    ->orderBy('order', 'ASC')
                                    ->get();

            $project_id = null;
            $task_count = 0;
            foreach($stages as $stage)
                $task_count = $task_count + $stage->getTasksByUserType($project_id)->count();

            $activities = array();

            return view('sections.projects.index', compact('projects', 'project_id', 'stages', 'task_count', 'activities'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
