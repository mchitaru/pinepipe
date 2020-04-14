<?php

namespace App\Http\Controllers;

use App\Activity;
use App\TaskChecklist;
use App\Client;
use App\TaskComment;
use App\Invoice;
use App\Lead;
use App\Milestone;
use App\PaymentPlan;
use App\Project;
use App\ProjectFile;
use App\ProjectStage;
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

        if($user->can('manage project'))
        {
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

            if ($request->ajax())
            {
                return view('projects.index', ['projects' => $projects])->render();
            }

            return view('projects.page', compact('projects'));
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
                        ->prepend('(myself)', \Auth::user()->id);

        $clients = Client::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $leads   = Lead::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('projects.create', compact('clients', 'users', 'leads', 'client_id'));
    }


    public function store(ProjectStoreRequest $request)
    {
        $post = $request->validated();

        if(\Auth::user()->checkProjectLimit())
        {
            $project = Project::createProject($post);

            $request->session()->flash('success', __('Project successfully created.'));

            $url = redirect()->route('projects.show', $project->id)->getTargetUrl();
            return "<script>window.location='{$url}'</script>";
        }
        else
        {
            $request->session()->flash('error', __('Your have reached your project limit. Please upgrade your subscription to add more projects!'));
        }

        $url = redirect()->route('profile.show')->getTargetUrl().'/#subscription';
        return "<script>window.location='{$url}'</script>";
    }


    public function edit(Project $project)
    {
        $clients = Client::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $users   = User::where('created_by', '=', \Auth::user()->creatorId())
                        ->where('type', '!=', 'client')
                        ->get()
                        ->pluck('name', 'id')
                        ->prepend('(myself)', \Auth::user()->id);

        $leads   = Lead::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        $user_id = $project->users()->get()->pluck('id');

        $start_date = $project->start_date;
        $due_date = $project->due_date;

        return view('projects.edit', compact('project', 'clients', 'user_id', 'users', 'leads', 'start_date', 'due_date'));
    }

    public function update(ProjectUpdateRequest $request, Project $project)
    {
        if($request->ajax() && $request->isMethod('patch'))
        {
            return view('helpers.archive');
        }

        $post = $request->validated();

        $project->updateProject($post);

        $request->session()->flash('success', __('Project successfully updated.'));

        $url = redirect()->back()->getTargetUrl();
        return "<script>window.location='{$url}'</script>";
    }

    public function show(Project $project)
    {
        $user = \Auth::user();

        if(\Auth::user()->can('show project'))
        {
            clock()->startEvent('ProjectsController', "Load project");

            $project_id = $project->id;

            $stages = $project->stages()->get();

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
            $activities = $project->allActivities;

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

            $project->computeStatistics($user->last_projectstage()->id);

            clock()->endEvent('ProjectsController');

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

        $project->detachProject();

        $project->delete();

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
