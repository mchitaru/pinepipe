<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Checklist;
use App\Client;
use App\Comment;
use App\Invoice;
use App\Lead;
use App\Milestone;
use App\SubscriptionPlan;
use App\Project;
use App\ProjectFile;
use App\SubTask;
use App\Task;
use App\Timesheet;
use App\Expense;
use App\User;
use App\UserProject;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Requests\ProjectDestroyRequest;
use App\Utility;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectsController extends Controller
{

    public function index(Request $request)
    {
        $user = \Auth::user();

        if($user->can('view project'))
        {
            if (!$request->ajax())
            {
                return view('projects.page');
            }

            clock()->startEvent('ProjectsController.index', "Load projects");

            if($request['tag']){
                $status = array(array_search($request['tag'], Project::$status));
            }else{
                $status = array(array_search('active', Project::$status));
            }

            $projects = $user->projectsByUserType()
                            ->with(['tasks', 'users', 'client'])
                            ->whereIn('archived', $status)
                            ->where(function ($query) use ($request) {
                                $query->where('name','like','%'.$request['filter'].'%')
                                ->orWhereHas('client', function ($query) use($request) {

                                    $query->where('name','like','%'.$request['filter'].'%');
                                });
                            })
                            ->orderBy($request['sort']?$request['sort']:'name', $request['dir']?$request['dir']:'asc')
                            ->paginate(25, ['*'], 'project-page');

            clock()->endEvent('ProjectsController.index');

            return view('projects.index', ['projects' => $projects])->render();
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create(Request $request)
    {
        $client_id = $request['client_id'];

        $users   = User::where('created_by', '=', \Auth::user()->creatorId())
                        ->where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend(__('(myself)'), \Auth::user()->id);
        $user_id = \Auth::user()->id;

        $clients = Client::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $leads   = Lead::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('projects.create', compact('clients', 'users', 'user_id', 'leads', 'client_id'));
    }


    public function store(ProjectStoreRequest $request)
    {
        $post = $request->validated();

        if(\Auth::user()->checkProjectLimit())
        {
            $project = Project::createProject($post);

            $request->session()->flash('success', __('Project successfully created.'));

            $url = redirect()->route('projects.show', $project->id)->getTargetUrl();
            return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
        }
        else
        {
            $request->session()->flash('error', __('Your have reached your project limit. Please upgrade your subscription to add more projects!'));
        }

        $url = redirect()->route('profile.edit', \Auth::user()->handle())->getTargetUrl().'/#subscription';
        return $request->ajax() ? response()->json(['success', 'url'=>$url], 207) : redirect()->to($url);
    }


    public function edit(Project $project)
    {
        $clients = Client::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $users   = User::where('created_by', '=', \Auth::user()->creatorId())
                        ->where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend(__('(myself)'), \Auth::user()->id);

        $leads   = Lead::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        $user_id = $project->users()->get()->pluck('id');

        $start_date = $project->start_date;
        $due_date = $project->due_date;

        return view('projects.edit', compact('project', 'clients', 'user_id', 'users', 'leads', 'start_date', 'due_date'));
    }

    public function update(ProjectUpdateRequest $request, Project $project)
    {
        if($request->ajax() && $request->isMethod('patch') && !isset($request['archived']))
        {
            return view('helpers.archive');
        }

        $post = $request->validated();

        $project->updateProject($post);

        $request->session()->flash('success', __('Project successfully updated.'));

        return $request->ajax() ? response()->json(['success'], 207) : redirect()->back();
    }

    public function show(Request $request, Project $project)
    {
        $user = \Auth::user();

        if(\Auth::user()->can('view project'))
        {
            clock()->startEvent('ProjectsController', "Load project");

            $project_id = $project->id;

            $stages = $project->stages($request['sort'], $request['dir'], [])->get();

            $task_count = 0;
            foreach($stages as $stage)
            {
                $task_count += $stage->tasks->count();
            }

            $files = [];
            foreach($project->getMedia('projects') as $media)
            {
                $file = [];

                $file['file_name'] = $media->file_name;
                $file['size'] = $media->size;
                $file['download'] = route('projects.file.download',[$project->id, $media->id]);
                $file['delete'] = route('projects.file.delete',[$project->id, $media->id]);

                $files[] = $file;
            }

            $invoices = $project->invoices;
            $activities = $project->activities;

            if(\Auth::user()->type == 'company' || \Auth::user()->type == 'client')
            {
                $timesheets = $project->timesheets;
                $expenses = $project->expenses;
            }
            else
            {
                $timesheets = $project->timesheets()->where('user_id', '=', \Auth::user()->id)->get();
                $expenses = $project->expenses()->where('user_id', '=', \Auth::user()->id)->get();
            }

            $project->computeStatistics($user->getLastTaskStage()->id);

            clock()->endEvent('ProjectsController');

            if ($request->ajax())
            {
                return view('tasks.index', compact('stages'))->render();
            }

            return view('projects.show', compact('project', 'project_id', 'stages', 'task_count', 'timesheets', 'invoices', 'expenses', 'files', 'activities'));
        }
        else
        {
            return Redirect::to(URL::previous())->with('error', __('Permission denied.'));
        }
    }

    public function destroy(ProjectDestroyRequest $request, Project $project)
    {
        if($request->ajax()){

            return view('helpers.destroy');
        }

        $project->delete();

        if(URL::previous() == route('projects.show', $project)){

            return Redirect::to(route('projects.index'))->with('success', __('Project successfully deleted'));
        }

        return Redirect::to(URL::previous())->with('success', __('Project successfully deleted'));
    }

    public function search($search)
    {
        if(\Auth::user()->type == 'client')
        {
            $objProject = Project::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->where('projects.client_id', '=', Auth::user()->client_id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach($objProject as $project)
            {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route('projects.show', [$project->id]),
                ];
            }

            $objTask = Task::select(
                [
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.client_id', '=', Auth::user()->client_id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('tasks.title', 'LIKE', $search . "%")->get();
            $arrTask = [];
            foreach($objTask as $task)
            {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route('projects.show', [$task->project_id]),
                ];
            }
        }
        else if(\Auth::user()->type == 'company')
        {
            $objProject = Project::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->where('projects.created_by', '=', \Auth::user()->id)->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach($objProject as $project)
            {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route('projects.show', [$project->id]),
                ];
            }

            $objTask = Task::select(
                [
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->where('projects.created_by', '=', \Auth::user()->id)->where('tasks.title', 'LIKE', $search . "%")->get();
            $arrTask = [];
            foreach($objTask as $task)
            {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route('projects.show', [$task->project_id]),
                ];
            }
        }
        else
        {
            $objProject = Project::select(
                [
                    'projects.id',
                    'projects.name',
                ]
            )->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('projects.name', 'LIKE', $search . "%")->get();
            $arrProject = [];
            foreach($objProject as $project)
            {
                $arrProject[] = [
                    'text' => $project->name,
                    'link' => route('projects.show', [$project->id]),
                ];
            }

            $objTask = Task::select(
                [
                    'tasks.project_id',
                    'tasks.title',
                ]
            )->join('projects', 'tasks.project_id', '=', 'projects.id')->join('user_projects', 'user_projects.project_id', '=', 'projects.id')->where('user_projects.user_id', '=', Auth::user()->id)->where('projects.created_by', '=', \Auth::user()->creatorId())->where('tasks.title', 'LIKE', $search . "%")->get();
            $arrTask = [];
            foreach($objTask as $task)
            {
                $arrTask[] = [
                    'text' => $task->title,
                    'link' => route('projects.show', [$task->project_id]),
                ];
            }
        }

        return json_encode(
            [
                'Projects' => $arrProject,
                'Tasks' => $arrTask,
            ]
        );
    }

}
