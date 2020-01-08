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
        localStorage.setItem('activeTab#projects', $(e.target).attr('href'));
    });

    var activeTab = localStorage.getItem('activeTab#projects');

    if(activeTab){
        $('.nav-tabs a[href="' + activeTab + '"]').tab('show');
    } 
    else{
        $('.nav-tabs a[href="#projects"]').tab('show');
    }
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
                                        <a  href="{{ route('project.taskboard',$project->id) }}" data-toggle="tooltip" data-original-title="{{__('Completed Tasks')}}">{{$completed_task}}/{{$total_task}}</a>
                                        @else
                                        <a  href="#" data-toggle="tooltip" data-original-title="{{__('Completed Tasks')}}">{{$completed_task}}/{{$total_task}}</a>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="material-icons mr-1">storefront</i>
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
                    <h3>Tasks</h3>
                    <button class="btn btn-round" data-toggle="modal" data-target="#task-add-modal">
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
                <div class="card-list">
                    <div class="card-list-head">
                    <h6>Evaluation</h6>
                    <div class="dropdown">
                        <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Rename</a>
                        <a class="dropdown-item text-danger" href="#">Archive</a>
                        </div>
                    </div>
                    </div>
                    <div class="card-list-body">

                    <div class="card card-task">
                        <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 75%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="card-body">
                        <div class="card-title">
                            <a href="#">
                            <h6 data-filter-by="text">Client objective meeting</h6>
                            </a>
                            <span class="text-small">Today</span>
                        </div>
                        <div class="card-meta">
                            <ul class="avatars">

                            <li>
                                <a href="#" data-toggle="tooltip" title="Kenny">
                                <img alt="Kenny Tran" class="avatar" src="assets/img/avatar-male-6.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="David">
                                <img alt="David Whittaker" class="avatar" src="assets/img/avatar-male-4.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="Sally">
                                <img alt="Sally Harper" class="avatar" src="assets/img/avatar-female-3.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="Kristina">
                                <img alt="Kristina Van Der Stroem" class="avatar" src="assets/img/avatar-female-4.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="Claire">
                                <img alt="Claire Connors" class="avatar" src="assets/img/avatar-female-1.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="Marcus">
                                <img alt="Marcus Simmons" class="avatar" src="assets/img/avatar-male-1.jpg" />
                                </a>
                            </li>

                            </ul>
                            <div class="d-flex align-items-center">
                            <i class="material-icons">playlist_add_check</i>
                            <span>3/4</span>
                            </div>
                            <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Mark as done</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Archive</a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="card card-task">
                        <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 20%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="card-body">
                        <div class="card-title">
                            <a href="#">
                            <h6 data-filter-by="text">Target market trend analysis</h6>
                            </a>
                            <span class="text-small">5 days</span>
                        </div>
                        <div class="card-meta">
                            <ul class="avatars">

                            <li>
                                <a href="#" data-toggle="tooltip" title="Peggy">
                                <img alt="Peggy Brown" class="avatar" src="assets/img/avatar-female-2.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="David">
                                <img alt="David Whittaker" class="avatar" src="assets/img/avatar-male-4.jpg" />
                                </a>
                            </li>

                            </ul>
                            <div class="d-flex align-items-center">
                            <i class="material-icons">playlist_add_check</i>
                            <span>2/10</span>
                            </div>
                            <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Mark as done</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Archive</a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="card card-task">
                        <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="card-body">
                        <div class="card-title">
                            <a href="#">
                            <h6 data-filter-by="text">Assemble Outcomes Report for client</h6>
                            </a>
                            <span class="text-small">7 days</span>
                        </div>
                        <div class="card-meta">
                            <ul class="avatars">

                            <li>
                                <a href="#" data-toggle="tooltip" title="Marcus">
                                <img alt="Marcus Simmons" class="avatar" src="assets/img/avatar-male-1.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="Claire">
                                <img alt="Claire Connors" class="avatar" src="assets/img/avatar-female-1.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="David">
                                <img alt="David Whittaker" class="avatar" src="assets/img/avatar-male-4.jpg" />
                                </a>
                            </li>

                            </ul>
                            <div class="d-flex align-items-center">
                            <i class="material-icons">playlist_add_check</i>
                            <span>0/6</span>
                            </div>
                            <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Mark as done</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Archive</a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    </div>
                </div>
                <div class="card-list">
                    <div class="card-list-head">
                    <h6>Ideation</h6>
                    <div class="dropdown">
                        <button class="btn-options" type="button" id="cardlist-dropdown-button-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Rename</a>
                        <a class="dropdown-item text-danger" href="#">Archive</a>
                        </div>
                    </div>
                    </div>
                    <div class="card-list-body">

                    <div class="card card-task">
                        <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="card-body">
                        <div class="card-title">
                            <a href="#">
                            <h6 data-filter-by="text">Create brand mood boards</h6>
                            </a>
                            <span class="text-small">14 days</span>
                        </div>
                        <div class="card-meta">
                            <ul class="avatars">

                            <li>
                                <a href="#" data-toggle="tooltip" title="Sally">
                                <img alt="Sally Harper" class="avatar" src="assets/img/avatar-female-3.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="Claire">
                                <img alt="Claire Connors" class="avatar" src="assets/img/avatar-female-1.jpg" />
                                </a>
                            </li>

                            </ul>
                            <div class="d-flex align-items-center">
                            <i class="material-icons">playlist_add_check</i>
                            <span>1/4</span>
                            </div>
                            <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Mark as done</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Archive</a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="card card-task">
                        <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="card-body">
                        <div class="card-title">
                            <a href="#">
                            <h6 data-filter-by="text">Produce broad concept directions</h6>
                            </a>
                            <span class="text-small">21 days</span>
                        </div>
                        <div class="card-meta">
                            <ul class="avatars">

                            <li>
                                <a href="#" data-toggle="tooltip" title="Peggy">
                                <img alt="Peggy Brown" class="avatar" src="assets/img/avatar-female-2.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="David">
                                <img alt="David Whittaker" class="avatar" src="assets/img/avatar-male-4.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="Ravi">
                                <img alt="Ravi Singh" class="avatar" src="assets/img/avatar-male-3.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="Sally">
                                <img alt="Sally Harper" class="avatar" src="assets/img/avatar-female-3.jpg" />
                                </a>
                            </li>

                            </ul>
                            <div class="d-flex align-items-center">
                            <i class="material-icons">playlist_add_check</i>
                            <span>0/5</span>
                            </div>
                            <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Mark as done</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Archive</a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="card card-task">
                        <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="card-body">
                        <div class="card-title">
                            <a href="#">
                            <h6 data-filter-by="text">Present concepts and establish direction</h6>
                            </a>
                            <span class="text-small">28 days</span>
                        </div>
                        <div class="card-meta">
                            <ul class="avatars">

                            <li>
                                <a href="#" data-toggle="tooltip" title="Kristina">
                                <img alt="Kristina Van Der Stroem" class="avatar" src="assets/img/avatar-female-4.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="Peggy">
                                <img alt="Peggy Brown" class="avatar" src="assets/img/avatar-female-2.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="Ravi">
                                <img alt="Ravi Singh" class="avatar" src="assets/img/avatar-male-3.jpg" />
                                </a>
                            </li>

                            </ul>
                            <div class="d-flex align-items-center">
                            <i class="material-icons">playlist_add_check</i>
                            <span>0/3</span>
                            </div>
                            <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Mark as done</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Archive</a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    </div>
                </div>
                <div class="card-list">
                    <div class="card-list-head">
                    <h6>Design</h6>
                    <div class="dropdown">
                        <button class="btn-options" type="button" id="cardlist-dropdown-button-3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Rename</a>
                        <a class="dropdown-item text-danger" href="#">Archive</a>
                        </div>
                    </div>
                    </div>
                    <div class="card-list-body">

                    <div class="card card-task">
                        <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="card-body">
                        <div class="card-title">
                            <a href="#">
                            <h6 data-filter-by="text">Produce realised brand package</h6>
                            </a>
                            <span class="text-small">Unscheduled</span>
                        </div>
                        <div class="card-meta">
                            <ul class="avatars">

                            <li>
                                <a href="#" data-toggle="tooltip" title="Marcus">
                                <img alt="Marcus Simmons" class="avatar" src="assets/img/avatar-male-1.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="Harry">
                                <img alt="Harry Xai" class="avatar" src="assets/img/avatar-male-2.jpg" />
                                </a>
                            </li>

                            <li>
                                <a href="#" data-toggle="tooltip" title="Kristina">
                                <img alt="Kristina Van Der Stroem" class="avatar" src="assets/img/avatar-female-4.jpg" />
                                </a>
                            </li>

                            </ul>
                            <div class="d-flex align-items-center">
                            <i class="material-icons">playlist_add_check</i>
                            <span>-/-</span>
                            </div>
                            <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-7" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Mark as done</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Archive</a>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    </div>
                </div>
                <!--end of content list body-->
                </div>
                <!--end of content list-->
        </div>
        </div>
    </div>
    </div>
@endsection
