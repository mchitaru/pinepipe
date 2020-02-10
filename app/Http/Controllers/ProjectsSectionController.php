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
        $user = \Auth::user();

        if($user->can('manage project'))
        {
            clock()->startEvent('ProjectsSectionController', "Load projects");

            $projects = $user->projectsByUserType()
                            ->with(['tasks', 'users', 'client'])
                            ->paginate(25, ['*'], 'project-page');

            clock()->endEvent('ProjectsSectionController');
            
            return view('sections.projects.index', compact('projects'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
