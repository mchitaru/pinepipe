@extends('layouts.app')

@php
use Carbon\Carbon;
use App\Project;

$current_user=\Auth::user();
$dz_id = 'project-files-dz';

if(Gate::check('view task')){
    $default_tab = '#tasks';
}else{
    $default_tab = '#timesheets';
}

@endphp

@push('stylesheets')
@endpush

@push('scripts')
<script>

$(function() {

    localStorage.setItem('sort', 'priority');
    localStorage.setItem('dir', 'asc');
    localStorage.setItem('tag', '');

    updateFilters();

    $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
        window.history.replaceState(null, null, $(e.target).attr('href'));
        window.location.hash = $(e.target).attr('href');

        var id = $(e.target).attr("href");
        sessionStorage.setItem('projects.tab', id);
    });

    var hash = window.location.hash ? window.location.hash : sessionStorage.getItem('projects.tab');

    if(hash == null) hash = '{{$default_tab}}';

    $('a[data-toggle="tab"][href="' + hash + '"]').tab('show');

    initDropzone('#{{$dz_id}}', '{{route('projects.file.upload',[$project->id])}}', '{{$project->id}}', {!! json_encode($files) !!});
});

</script>

@endpush

@section('page-title')
    {{__('Project Details')}}
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h1>{{$project->name}}</h1>
                    <div class="pl-2">
                        <i class="material-icons mr-1">business</i>
                        <a href="{{ route('clients.show',$project->client->id) }}" data-toggle="tooltip" data-title="{{__('Client')}}">
                            {{ (!empty($project->client)?$project->client->name:'') }}
                        </a>
                    </div>
                </div>
            <p class="lead">{!! nl2br(e($project->description)) !!}</p>
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <ul class="avatars">
                        @foreach($project->users as $user)
                        <li>
                            <a href="{{ route('users.index',$user->id) }}" data-toggle="tooltip" title="{{$user->name}}">
                                {!!Helpers::buildUserAvatar($user)!!}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @can('view project')
                    <a href="{{ route('projects.invite.create', $project->id)  }}" class="btn btn-round" data-remote="true" data-type="text" data-toggle="tooltip" title="{{__('Invite Users')}}">
                        <i class="material-icons">add</i>
                    </a>
                    @endcan
                </div>
                <div class="dropdown">
                    <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
                      <i class="material-icons">expand_more</i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if(Gate::check('edit project') || Gate::check('delete project'))

                        @can('edit project')
                            <a class="dropdown-item" href="{{ route('projects.edit', $project->id) }}" data-remote="true" data-type="text">
                                {{__('Edit Project')}}
                            </a>
                        @endcan

                        <div class="dropdown-divider"></div>
                        @can('view task')
                            <a class="dropdown-item" href="{{ route('tasks.board', $project->id) }}" data-title="{{__('Task Board')}}">
                                {{__('Task Board')}}
                            </a>
                        @endcan
                        <a class="dropdown-item disabled" href="#">{{__('Share')}}</a>
                        <div class="dropdown-divider"></div>

                        @can('edit project')
                            @if(!$project->archived)
                                <a class="dropdown-item text-danger" href="{{ route('projects.update', $project->id) }}" data-method="PATCH" data-remote="true" data-type="text">
                                    {{__('Archive')}}
                                </a>
                            @else
                                <a href="{{ route('projects.update', $project->id) }}" class="dropdown-item text-danger" data-params="archived=0" data-method="PATCH" data-remote="true" data-type="text">
                                    {{__('Restore')}}
                                </a>
                            @endif
                        @endcan

                        @can('delete project')
                            <a class="dropdown-item text-danger" href="{{ route('projects.destroy', $project->id) }}" data-method="delete" data-remote="true" data-type="text">
                                {{__('Delete')}}
                            </a>
                        @endcan

                        @endif
                    </div>
                </div>
            </div>
            <div>
                <div class="d-flex flex-row-reverse">
                    <small class="card-text" style="float:right;">{{$project->progress}}%</small>
                </div>
                <div class="progress mt-0">
                        <div class="progress-bar {{Helpers::getProgressColor($project->progress)}}" style="width:{{$project->progress}}%;"></div>
                </div>
                <div class="d-flex justify-content-between text-small">
                <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Status')}}">
                    @if(!$project->archived)
                        <span class="badge badge-info">{{__('active')}}</span>
                    @else
                        <span class="badge badge-success">{{__('archived')}}</span>
                    @endif
                </div>
                <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Completed')}}">
                    <i class="material-icons">playlist_add_check</i>
                    <span>{{$project->completed_tasks}}/{{$project->tasks->count()}}</span>
                </div>
                <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Members')}}">
                    <i class="material-icons">people</i>
                    <span>{{$project->users()->count()}}</span>
                </div>
                    {!!\Helpers::showDateForHumans($project->due_date, __('Due'))!!}
                </div>
            </div>
            </div>
            <ul class="nav nav-tabs nav-fill" role="tablist">
            @can('view task')
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tasks" role="tab" aria-controls="tasks" aria-selected="true">{{__('Tasks')}}
                    <span class="badge badge-secondary">{{ $project->tasks->count() }}</span>
                </a>
            </li>
            @endcan
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#timesheets" role="tab" aria-controls="timesheets" aria-selected="false">{{__('Timesheets')}}
                    <span class="badge badge-secondary">{{ $timesheets->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#invoices" role="tab" aria-controls="invoices" aria-selected="false">{{__('Invoices')}}
                    <span class="badge badge-secondary">{{ $invoices->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#expenses" role="tab" aria-controls="expenses" aria-selected="false">{{__('Expenses')}}
                    <span class="badge badge-secondary">{{ $expenses->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#project-files" role="tab" aria-controls="project-files" aria-selected="false">{{__('Files')}}
                    <span class="badge badge-secondary">{{ count($files) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">{{__('Activity')}}</a>
            </li>
            </ul>
            <div class="tab-content">
            <div class="tab-pane fade show" id="tasks" role="tabpanel" data-filter-list="card-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Tasks')}}</h3>

                    @can('create task')
                        <a href="{{ route('tasks.create')  }}" class="btn btn-round" data-params="project_id={{$project->id}}" data-remote="true" data-type="text" >
                            <i class="material-icons">add</i>
                        </a>
                    @endcan
                </div>
                <div class="filter-container col-auto">
                    <div class="filter-controls">
                        <div>{{__('Sort')}}:</div>
                        <a class="sort" href="#" data-sort="priority">{{__('Priority')}}</a>
                        <a class="sort" href="#" data-sort="due_date">{{__('Date')}}</a>
                    </div>
                </div>
                <form class="col-md-auto">
                    <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="material-icons">filter_list</i>
                        </span>
                    </div>
                    <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter tasks")}}" aria-label="Filter Tasks">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body filter-list paginate-container">@can('view task')@include('tasks.index')@endcan</div>
                <!--end of content list-->
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show" id="timesheets" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Timesheets')}}</h3>

                    @can('create timesheet')
                        <a href="{{ route('timesheets.create') }}" class="btn btn-round" data-params="project_id={{$project->id}}" data-remote="true" data-type="text" >
                            <i class="material-icons">add</i>
                        </a>
                    @endcan

                </div>
                <form class="col-md-auto">
                    <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="material-icons">filter_list</i>
                        </span>
                    </div>
                    <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter timesheets")}}" aria-label="Filter timesheets">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">@can('view timesheet')@include('timesheets.index')@endcan</div>
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show" id="invoices" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Invoices')}}</h3>

                    @can('create invoice')
                        <a href="{{ route('invoices.create')  }}" class="btn btn-round" data-params="project_id={{$project->id}}" data-remote="true" data-type="text" >
                            <i class="material-icons">add</i>
                        </a>
                    @endcan
                </div>
                <form class="col-md-auto">
                    <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="material-icons">filter_list</i>
                        </span>
                    </div>
                    <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter Invoices")}}" aria-label="Filter Invoices">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">@can('view invoice')@include('invoices.index')@endcan</div>
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show" id="expenses" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Expenses')}}</h3>

                    @can('create expense')
                        <a href="{{ route('expenses.create')  }}" class="btn btn-round" data-params="project_id={{$project->id}}" data-remote="true" data-type="text" >
                            <i class="material-icons">add</i>
                        </a>
                    @endcan
                </div>
                <form class="col-md-auto">
                    <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="material-icons">filter_list</i>
                        </span>
                    </div>
                    <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter Expenses")}}" aria-label="Filter Expenses">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">@can('view expense')@include('expenses.index')@endcan</div>
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show" id="project-files" role="tabpanel" data-filter-list="dropzone-previews">
                <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>{{__('Files')}}</h3>
                    </div>
                    <form class="col-md-auto">
                        <div class="input-group input-group-round">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">filter_list</i>
                            </span>
                            </div>
                            <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter files")}}" aria-label="Filter Tasks">
                        </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body row">@include('files.index')</div>
                </div>
                <!--end of content list-->
            </div>
            @if(\Auth::user()->type!='client')
            <div class="tab-pane fade" id="activity" role="tabpanel" data-filter-list="list-group-activity">
                <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>{{__('Activity')}}</h3>
                    </div>
                    <form class="col-md-auto">
                    <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                        </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter activity")}}" aria-label="Filter activity">
                    </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">@include('activity.index')</div>
                </div>
                <!--end of content list-->
            </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection
