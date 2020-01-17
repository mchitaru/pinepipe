@extends('layouts.app')

@php
    $profile=asset(Storage::url('avatar/'));
@endphp

@push('stylesheets')
@endpush

@push('scripts')

<script>

// keep active tab
$(document).ready(function() {

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        window.location.hash = $(e.target).attr('href');
        $(window).scrollTop(0);
    });

    var hash = window.location.hash ? window.location.hash : '#projects';
    
    $('.nav-tabs a[href="' + hash + '"]').tab('show');
    
});
    
</script>
    
@endpush

@section('page-title')
    {{__('Projects')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Projects')}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#team-manage-modal">Edit Team</a>
            <a class="dropdown-item" href="#">Share</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="#">Leave</a>

        </div>
    </div>
</div>
@endsection

@section('content')
    <div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="page-header">
        </div>
        <ul class="nav nav-tabs nav-fill" role="tablist">
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#projects" role="tab" aria-controls="projects" aria-selected="true">Projects
                <span class="badge badge-secondary">{{ count($projects) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tasks" role="tab" aria-controls="tasks" aria-selected="false">Tasks</a>
        </li>
        </ul>
        <div class="tab-content">
        <div class="tab-pane fade show" id="projects" role="tabpanel" data-filter-list="content-list-body">
            <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>{{__('Projects')}}</h3>
                    @can('create project')
                    <button class="btn btn-round" data-url="{{ route('projects.create') }}" data-ajax-popup="true" data-title="{{__('Create New Project')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
                        <i class="material-icons">add</i>
                    </button>
                    @endcan
                    </div>
                    <form class="col-md-auto">
                    <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                        </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="Filter projects" aria-label="Filter Projects">
                    </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body row">

                @foreach ($projects as $project)

                @php
                    $permissions=$project->client_project_permission();
                    $perArr=(!empty($permissions)? explode(',',$permissions->permissions):[]);

                    $project_last_stage = ($project->project_last_stage($project->id)? $project->project_last_stage($project->id)->id:'');

                    $total_task = $project->project_total_task($project->id);
                    $completed_task=$project->project_complete_task($project->id,$project_last_stage);
                    
                    $percentage=0;
                    if($total_task!=0){
                        $percentage = intval(($completed_task / $total_task) * 100);
                    }

                    $label='';
                    if($percentage<=15){
                        $label='bg-danger';
                    }else if ($percentage > 15 && $percentage <= 33) {
                        $label='bg-warning';
                    } else if ($percentage > 33 && $percentage <= 70) {
                        $label='bg-primary';
                    } else {
                        $label='bg-success';
                    }

                @endphp

                    <div class="col-lg-6">
                        <div class="card card-project">
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{$percentage}}%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <div class="card-body">
                                @if(Gate::check('edit project') || Gate::check('delete project') || Gate::check('create user'))
                                    @if($project->is_active==1)
                                        <div class="dropdown card-options">
                                            <button class="btn-options" type="button" id="project-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                            @can('edit project')
                                                <a class="dropdown-item" href="#" data-url="{{ route('projects.edit',$project->id) }}" data-ajax-popup="true" data-title="{{__('Edit Project')}}">
                                                    {{__('Edit')}}
                                                </a>
                                            @endcan
                                            @can('manage invite user')
                                                <a class="dropdown-item" href="#" data-url="{{ route('project.invite',$project->id) }}" data-ajax-popup="true" data-title="{{__('Add User')}}" class="" data-toggle="tooltip" data-original-title="{{__('Add User')}}">
                                                    {{__('Add User')}}
                                                </a>
                                            @endcan
                                            <div class="dropdown-divider"></div>
                                            @can('delete project')
                                                <a class="dropdown-item text-danger" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$project->id}}').submit();">
                                                    {{__('Delete')}}
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id],'id'=>'delete-form-'.$project->id]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                            </div>
                                        </div>
                                    @endif
                                @endif
                                <div class="card-title d-flex justify-content-between align-items-center">
                                    @can('show project')
                                    @if($project->is_active==1)
                                        <a href="{{ route('projects.show',$project->id) }}">
                                            <h5 data-filter-by="text">{{ $project->name }}</h5>
                                        </a>
                                    @else
                                        <a href="#">
                                            <h5 data-filter-by="text">{{ $project->name }}</h5>
                                        </a>
                                    @endif
                                    @else
                                        <a href="#">
                                            <h5 data-filter-by="text">{{ $project->name }}</h5>
                                        </a>
                                    @endcan
                                    @foreach($project_status as $key => $status)
                                    @if($key== $project->status)
                                        <span class="badge badge-secondary">{{ $status}}</span>
                                    @endif
                                    @endforeach
                                </div>
                                <ul class="avatars">

                                    @foreach($project->project_user() as $project_user)
                                    <li>
                                        @if($project->is_active==1 && !empty($project_user))
                                        <a href="{{ route('users.index',$project_user->id) }}" data-toggle="tooltip" data-original-title="{{(!empty($project_user)?$project_user->name:'')}}">
                                            <img alt="{{(!empty($project_user)?$project_user->name:'')}}" class="avatar" src="{{(!empty($project_user->avatar)? $profile.'/'.$project_user->avatar:$profile.'/avatar.png')}}" data-filter-by="alt" />
                                        </a>
                                        @else
                                        <a data-toggle="tooltip" data-original-title="{{(!empty($project_user)?$project_user->name:'')}}">
                                            <img alt="{{(!empty($project_user)?$project_user->name:'')}}" class="avatar" src="{{(!empty($project_user->avatar)? $profile.'/'.$project_user->avatar:$profile.'/avatar.png')}}" data-filter-by="alt" />
                                        </a>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                                <div class="card-meta d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="material-icons mr-1">playlist_add_check</i>
                                        @if($project->is_active==1)
                                        <a  href="{{ route('projects.show',$project->id) }}" data-toggle="tooltip" data-original-title="{{__('Completed Tasks')}}">{{$completed_task}}/{{$total_task}}</a>
                                        @else
                                        <a  href="#" data-toggle="tooltip" data-original-title="{{__('Completed Tasks')}}">{{$completed_task}}/{{$total_task}}</a>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="material-icons mr-1">person</i>
                                        @if($project->is_active==1 && !empty($project->client()))
                                        <a href="{{ route('clients.index',$project->client()->id) }}" data-toggle="tooltip" data-original-title="{{__('Client')}}" data-filter-by="text">{{(!empty($project->client())?$project->client()->name:'')}}</a>
                                        @else
                                        <a data-toggle="tooltip" data-original-title="{{__('Client')}}" data-filter-by="text">{{(!empty($project->client())?$project->client()->name:'')}}</a>
                                        @endif
                                    </div>
                                    <span class="text-small" data-filter-by="text">{{__('Due on ')}}
                                        {{ \Auth::user()->dateFormat($project->due_date) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
				</div>
            <!--end of content list body-->
            </div>
            <!--end of content list-->
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show" id="tasks" role="tabpanel" data-filter-list="card-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Tasks')}}</h3>
                    <button class="btn btn-round" data-url="{{ route('tasks.create') }}" data-ajax-popup="true" data-title="{{__('Add')}}" data-toggle="tooltip" data-original-title="{{__('Add')}}">
                        <i class="material-icons">add</i>
                    </button>
                </div>
                <form class="col-md-auto">
                    <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="material-icons">filter_list</i>
                        </span>
                    </div>
                    <input type="search" class="form-control filter-list-input" placeholder="Filter tasks" aria-label="Filter Tasks">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">

                    @include ('tasks.index');
                <!--end of content list body-->
                </div>
                <!--end of content list-->
        </div>
        </div>
    </div>
    </div>
@endsection
