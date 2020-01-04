@extends('layouts.app')

@php
    $profile=asset(Storage::url('avatar/'));
@endphp

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('page-title')
    {{__('Project Detail')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Project')}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
          <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            @can('manage task')
            @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show task',$perArr)))
                <a class="dropdown-item" href="{{ route('project.taskboard',$project->id) }}" data-ajax-popup="true" data-title="{{__('Task Kanban')}}" data-toggle="tooltip" data-original-title="{{__('Task Kanban')}}">
                    {{__('Kanban Board')}}
                </a>
            @endif
            @endcan
            @can('edit project')
                <a class="dropdown-item" href="#" data-url="{{ route('projects.edit',$project->id) }}" data-ajax-popup="true" data-title="{{__('Edit Project')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                    {{__('Edit Project')}}
                </a>
            @endcan
            <a class="dropdown-item" href="#">Share</a>
            <a class="dropdown-item" href="#">Mark as Complete</a>
            <div class="dropdown-divider"></div>
            @can('delete task')
                <a class="dropdown-item text-danger" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$project->id}}').submit();">
                    {{__('Archive')}}
                </a>
                {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id],'id'=>'delete-form-'.$project->id]) !!}
                {!! Form::close() !!}
            @endcan

        </div>
    </div>
</div>
@endsection

@section('content')
    @php
        $permissions=$project->client_project_permission();
        $perArr=(!empty($permissions)? explode(',',$permissions->permissions):[]);
        $project_last_stage = ($project->project_last_stage($project->id))? $project->project_last_stage($project->id)->id:'';

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

    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="page-header">
            <h1>{{$project->name}}</h1>
            <p class="lead">{{ $project->description }}</p>
            <div class="d-flex align-items-center">
                <ul class="avatars">

                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="Claire Connors">
                    <img alt="Claire Connors" class="avatar" src="assets/img/avatar-female-1.jpg" />
                    </a>
                </li>

                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="Marcus Simmons">
                    <img alt="Marcus Simmons" class="avatar" src="assets/img/avatar-male-1.jpg" />
                    </a>
                </li>

                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="Peggy Brown">
                    <img alt="Peggy Brown" class="avatar" src="assets/img/avatar-female-2.jpg" />
                    </a>
                </li>

                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="Harry Xai">
                    <img alt="Harry Xai" class="avatar" src="assets/img/avatar-male-2.jpg" />
                    </a>
                </li>

                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="Sally Harper">
                    <img alt="Sally Harper" class="avatar" src="assets/img/avatar-female-3.jpg" />
                    </a>
                </li>

                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="Ravi Singh">
                    <img alt="Ravi Singh" class="avatar" src="assets/img/avatar-male-3.jpg" />
                    </a>
                </li>

                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="Kristina Van Der Stroem">
                    <img alt="Kristina Van Der Stroem" class="avatar" src="assets/img/avatar-female-4.jpg" />
                    </a>
                </li>

                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="David Whittaker">
                    <img alt="David Whittaker" class="avatar" src="assets/img/avatar-male-4.jpg" />
                    </a>
                </li>

                </ul>
                <button class="btn btn-round" data-toggle="modal" data-target="#user-manage-modal">
                <i class="material-icons">add</i>
                </button>
            </div>
            <div>
                <div class="progress">
                <div class="progress-bar bg-success" style="width:25%;"></div>
                </div>
                <div class="d-flex justify-content-between text-small">
                <div class="d-flex align-items-center">
                    <i class="material-icons">playlist_add_check</i>
                    <span>3/12</span>
                </div>
                <span>Due 9 days</span>
                </div>
            </div>
            </div>
            <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tasks" role="tab" aria-controls="tasks" aria-selected="true">Tasks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">Activity</a>
            </li>
            </ul>
            <div class="tab-content">
            <div class="tab-pane fade show active" id="tasks" role="tabpanel" data-filter-list="card-list-body">
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
            <!--end of tab-->
            <div class="tab-pane fade" id="files" role="tabpanel" data-filter-list="dropzone-previews">
                <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>Files</h3>
                    </div>
                    <form class="col-md-auto">
                    <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                        </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="Filter files" aria-label="Filter Tasks">
                    </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body row">
                    <div class="col">
                    <ul class="d-none dz-template">
                        <li class="list-group-item dz-preview dz-file-preview">
                        <div class="media align-items-center dz-details">
                            <ul class="avatars">
                            <li>
                                <div class="avatar bg-primary dz-file-representation">
                                <i class="material-icons">attach_file</i>
                                </div>
                            </li>
                            <li>
                                <img alt="David Whittaker" src="assets/img/avatar-male-4.jpg" class="avatar" data-title="David Whittaker" data-toggle="tooltip" />
                            </li>
                            </ul>
                            <div class="media-body d-flex justify-content-between align-items-center">
                            <div class="dz-file-details">
                                <a href="#" class="dz-filename">
                                <span data-dz-name></span>
                                </a>
                                <br>
                                <span class="text-small dz-size" data-dz-size></span>
                            </div>
                            <img alt="Loader" src="assets/img/loader.svg" class="dz-loading" />
                            <div class="dropdown">
                                <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Download</a>
                                <a class="dropdown-item" href="#">Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#" data-dz-remove>Delete</a>
                                </div>
                            </div>
                            <button class="btn btn-danger btn-sm dz-remove" data-dz-remove>
                                Cancel
                            </button>
                            </div>
                        </div>
                        <div class="progress dz-progress">
                            <div class="progress-bar dz-upload" data-dz-uploadprogress></div>
                        </div>
                        </li>
                    </ul>
                    <form class="dropzone" action="https://mediumra.re/dropzone/upload.php">
                        <span class="dz-message">Drop files here or click here to upload</span>
                    </form>

                    <ul class="list-group list-group-activity dropzone-previews flex-column-reverse">

                        <li class="list-group-item">
                        <div class="media align-items-center">
                            <ul class="avatars">
                            <li>
                                <div class="avatar bg-primary">
                                <i class="material-icons">insert_drive_file</i>
                                </div>
                            </li>
                            <li>
                                <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar" data-title="Peggy Brown" data-toggle="tooltip" data-filter-by="data-title" />
                            </li>
                            </ul>
                            <div class="media-body d-flex justify-content-between align-items-center">
                            <div>
                                <a href="#" data-filter-by="text">client-questionnaire</a>
                                <br>
                                <span class="text-small" data-filter-by="text">48kb Text Doc</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Download</a>
                                <a class="dropdown-item" href="#">Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Delete</a>
                                </div>
                            </div>
                            </div>
                        </div>
                        </li>

                        <li class="list-group-item">
                        <div class="media align-items-center">
                            <ul class="avatars">
                            <li>
                                <div class="avatar bg-primary">
                                <i class="material-icons">folder</i>
                                </div>
                            </li>
                            <li>
                                <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar" data-title="Harry Xai" data-toggle="tooltip" data-filter-by="data-title" />
                            </li>
                            </ul>
                            <div class="media-body d-flex justify-content-between align-items-center">
                            <div>
                                <a href="#" data-filter-by="text">moodboard_images</a>
                                <br>
                                <span class="text-small" data-filter-by="text">748kb ZIP</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Download</a>
                                <a class="dropdown-item" href="#">Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Delete</a>
                                </div>
                            </div>
                            </div>
                        </div>
                        </li>

                        <li class="list-group-item">
                        <div class="media align-items-center">
                            <ul class="avatars">
                            <li>
                                <div class="avatar bg-primary">
                                <i class="material-icons">image</i>
                                </div>
                            </li>
                            <li>
                                <img alt="Ravi Singh" src="assets/img/avatar-male-3.jpg" class="avatar" data-title="Ravi Singh" data-toggle="tooltip" data-filter-by="data-title" />
                            </li>
                            </ul>
                            <div class="media-body d-flex justify-content-between align-items-center">
                            <div>
                                <a href="#" data-filter-by="text">possible-hero-image</a>
                                <br>
                                <span class="text-small" data-filter-by="text">1.2mb JPEG image</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Download</a>
                                <a class="dropdown-item" href="#">Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Delete</a>
                                </div>
                            </div>
                            </div>
                        </div>
                        </li>

                        <li class="list-group-item">
                        <div class="media align-items-center">
                            <ul class="avatars">
                            <li>
                                <div class="avatar bg-primary">
                                <i class="material-icons">insert_drive_file</i>
                                </div>
                            </li>
                            <li>
                                <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar" data-title="Claire Connors" data-toggle="tooltip" data-filter-by="data-title" />
                            </li>
                            </ul>
                            <div class="media-body d-flex justify-content-between align-items-center">
                            <div>
                                <a href="#" data-filter-by="text">LandingPrototypes</a>
                                <br>
                                <span class="text-small" data-filter-by="text">415kb Sketch Doc</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Download</a>
                                <a class="dropdown-item" href="#">Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Delete</a>
                                </div>
                            </div>
                            </div>
                        </div>
                        </li>

                        <li class="list-group-item">
                        <div class="media align-items-center">
                            <ul class="avatars">
                            <li>
                                <div class="avatar bg-primary">
                                <i class="material-icons">insert_drive_file</i>
                                </div>
                            </li>
                            <li>
                                <img alt="David Whittaker" src="assets/img/avatar-male-4.jpg" class="avatar" data-title="David Whittaker" data-toggle="tooltip" data-filter-by="data-title" />
                            </li>
                            </ul>
                            <div class="media-body d-flex justify-content-between align-items-center">
                            <div>
                                <a href="#" data-filter-by="text">Branding-Proforma</a>
                                <br>
                                <span class="text-small" data-filter-by="text">15kb Text Document</span>
                            </div>
                            <div class="dropdown">
                                <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Download</a>
                                <a class="dropdown-item" href="#">Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Delete</a>
                                </div>
                            </div>
                            </div>
                        </div>
                        </li>

                    </ul>
                    </div>
                </div>
                </div>
                <!--end of content list-->
            </div>
            <div class="tab-pane fade" id="activity" role="tabpanel" data-filter-list="list-group-activity">
                <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>Activity</h3>
                    </div>
                    <form class="col-md-auto">
                    <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                        </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="Filter activity" aria-label="Filter activity">
                    </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    <ol class="list-group list-group-activity">

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">playlist_add_check</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Claire" src="assets/img/avatar-female-1.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Claire</span>
                            <span data-filter-by="text">completed the task</span><a href="#" data-filter-by="text">Set up client chat channel</a>
                            </div>
                            <span class="text-small" data-filter-by="text">Just now</span>
                        </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">person_add</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Ravi" src="assets/img/avatar-male-3.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Ravi</span>
                            <span data-filter-by="text">joined the project</span>
                            </div>
                            <span class="text-small" data-filter-by="text">5 hours ago</span>
                        </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">playlist_add</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Kristina" src="assets/img/avatar-female-4.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Kristina</span>
                            <span data-filter-by="text">added the task</span><a href="#" data-filter-by="text">Produce broad concept directions</a>
                            </div>
                            <span class="text-small" data-filter-by="text">Yesterday</span>
                        </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">playlist_add</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Marcus" src="assets/img/avatar-male-1.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Marcus</span>
                            <span data-filter-by="text">added the task</span><a href="#" data-filter-by="text">Present concepts and establish direction</a>
                            </div>
                            <span class="text-small" data-filter-by="text">Yesterday</span>
                        </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">person_add</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Sally" src="assets/img/avatar-female-3.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Sally</span>
                            <span data-filter-by="text">joined the project</span>
                            </div>
                            <span class="text-small" data-filter-by="text">2 days ago</span>
                        </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">date_range</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Claire" src="assets/img/avatar-female-1.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Claire</span>
                            <span data-filter-by="text">rescheduled the task</span><a href="#" data-filter-by="text">Target market trend analysis</a>
                            </div>
                            <span class="text-small" data-filter-by="text">2 days ago</span>
                        </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">add</i>
                            </div>
                            </li>
                            <li>
                            <img alt="David" src="assets/img/avatar-male-4.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">David</span>
                            <span data-filter-by="text">started the project</span>
                            </div>
                            <span class="text-small" data-filter-by="text">12 days ago</span>
                        </div>
                        </div>
                    </li>

                    </ol>
                </div>
                </div>
                <!--end of content list-->
            </div>
            </div>
            <form class="modal fade" id="user-manage-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Users</h5>
                    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
                    <i class="material-icons">close</i>
                    </button>
                </div>
                <!--end of modal head-->
                <div class="modal-body">
                    <div class="users-manage" data-filter-list="form-group-users">
                    <div class="mb-3">
                        <ul class="avatars text-center">

                        <li>
                            <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar" data-toggle="tooltip" data-title="Claire Connors" />
                        </li>

                        <li>
                            <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar" data-toggle="tooltip" data-title="Marcus Simmons" />
                        </li>

                        <li>
                            <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar" data-toggle="tooltip" data-title="Peggy Brown" />
                        </li>

                        <li>
                            <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar" data-toggle="tooltip" data-title="Harry Xai" />
                        </li>

                        </ul>
                    </div>
                    <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                        </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="Filter members" aria-label="Filter Members">
                    </div>
                    <div class="form-group-users">

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-1" checked>
                        <label class="custom-control-label" for="user-manage-1">
                            <span class="d-flex align-items-center">
                            <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Claire Connors</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-2" checked>
                        <label class="custom-control-label" for="user-manage-2">
                            <span class="d-flex align-items-center">
                            <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Marcus Simmons</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-3" checked>
                        <label class="custom-control-label" for="user-manage-3">
                            <span class="d-flex align-items-center">
                            <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Peggy Brown</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-4" checked>
                        <label class="custom-control-label" for="user-manage-4">
                            <span class="d-flex align-items-center">
                            <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Harry Xai</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-5">
                        <label class="custom-control-label" for="user-manage-5">
                            <span class="d-flex align-items-center">
                            <img alt="Sally Harper" src="assets/img/avatar-female-3.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Sally Harper</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-6">
                        <label class="custom-control-label" for="user-manage-6">
                            <span class="d-flex align-items-center">
                            <img alt="Ravi Singh" src="assets/img/avatar-male-3.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Ravi Singh</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-7">
                        <label class="custom-control-label" for="user-manage-7">
                            <span class="d-flex align-items-center">
                            <img alt="Kristina Van Der Stroem" src="assets/img/avatar-female-4.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Kristina Van Der Stroem</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-8">
                        <label class="custom-control-label" for="user-manage-8">
                            <span class="d-flex align-items-center">
                            <img alt="David Whittaker" src="assets/img/avatar-male-4.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">David Whittaker</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-9">
                        <label class="custom-control-label" for="user-manage-9">
                            <span class="d-flex align-items-center">
                            <img alt="Kerri-Anne Banks" src="assets/img/avatar-female-5.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Kerri-Anne Banks</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-10">
                        <label class="custom-control-label" for="user-manage-10">
                            <span class="d-flex align-items-center">
                            <img alt="Masimba Sibanda" src="assets/img/avatar-male-5.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Masimba Sibanda</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-11">
                        <label class="custom-control-label" for="user-manage-11">
                            <span class="d-flex align-items-center">
                            <img alt="Krishna Bajaj" src="assets/img/avatar-female-6.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Krishna Bajaj</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-12">
                        <label class="custom-control-label" for="user-manage-12">
                            <span class="d-flex align-items-center">
                            <img alt="Kenny Tran" src="assets/img/avatar-male-6.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Kenny Tran</span>
                            </span>
                        </label>
                        </div>

                    </div>
                    </div>
                </div>
                <!--end of modal body-->
                <div class="modal-footer">
                    <button role="button" class="btn btn-primary" type="submit">
                    Done
                    </button>
                </div>
                </div>
            </div>
            </form>

            <form class="modal fade" id="task-add-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Task</h5>
                    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
                    <i class="material-icons">close</i>
                    </button>
                </div>
                <!--end of modal head-->
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" id="task-add-details-tab" data-toggle="tab" href="#task-add-details" role="tab" aria-controls="task-add-details" aria-selected="true">Details</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="task-add-members-tab" data-toggle="tab" href="#task-add-members" role="tab" aria-controls="task-add-members" aria-selected="false">Members</a>
                    </li>
                </ul>
                <div class="modal-body">
                    <div class="tab-content">
                    <div class="tab-pane fade show active" id="task-add-details" role="tabpanel">
                        <h6>General Details</h6>
                        <div class="form-group row align-items-center">
                        <label class="col-3">Name</label>
                        <input class="form-control col" type="text" placeholder="Task name" name="task-name" />
                        </div>
                        <div class="form-group row">
                        <label class="col-3">Description</label>
                        <textarea class="form-control col" rows="3" placeholder="Task description" name="task-description"></textarea>
                        </div>
                        <hr>
                        <h6>Timeline</h6>
                        <div class="form-group row align-items-center">
                        <label class="col-3">Start Date</label>
                        <input class="form-control col" type="text" name="task-start" placeholder="Select a date" data-flatpickr data-default-date="2021-04-21" data-alt-input="true" />
                        </div>
                        <div class="form-group row align-items-center">
                        <label class="col-3">Due Date</label>
                        <input class="form-control col" type="text" name="task-due" placeholder="Select a date" data-flatpickr data-default-date="2021-09-15" data-alt-input="true" />

                        </div>
                        <div class="alert alert-warning text-small" role="alert">
                        <span>You can change due dates at any time.</span>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="task-add-members" role="tabpanel">
                        <div class="users-manage" data-filter-list="form-group-users">
                        <div class="mb-3">
                            <ul class="avatars text-center">

                            <li>
                                <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar" data-toggle="tooltip" data-title="Claire Connors" />
                            </li>

                            <li>
                                <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar" data-toggle="tooltip" data-title="Marcus Simmons" />
                            </li>

                            <li>
                                <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar" data-toggle="tooltip" data-title="Peggy Brown" />
                            </li>

                            <li>
                                <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar" data-toggle="tooltip" data-title="Harry Xai" />
                            </li>

                            </ul>
                        </div>
                        <div class="input-group input-group-round">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">filter_list</i>
                            </span>
                            </div>
                            <input type="search" class="form-control filter-list-input" placeholder="Filter members" aria-label="Filter Members">
                        </div>
                        <div class="form-group-users">

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-1" checked>
                            <label class="custom-control-label" for="task-user-1">
                                <span class="d-flex align-items-center">
                                <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Claire Connors</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-2" checked>
                            <label class="custom-control-label" for="task-user-2">
                                <span class="d-flex align-items-center">
                                <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Marcus Simmons</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-3" checked>
                            <label class="custom-control-label" for="task-user-3">
                                <span class="d-flex align-items-center">
                                <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Peggy Brown</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-4" checked>
                            <label class="custom-control-label" for="task-user-4">
                                <span class="d-flex align-items-center">
                                <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Harry Xai</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-5">
                            <label class="custom-control-label" for="task-user-5">
                                <span class="d-flex align-items-center">
                                <img alt="Sally Harper" src="assets/img/avatar-female-3.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Sally Harper</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-6">
                            <label class="custom-control-label" for="task-user-6">
                                <span class="d-flex align-items-center">
                                <img alt="Ravi Singh" src="assets/img/avatar-male-3.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Ravi Singh</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-7">
                            <label class="custom-control-label" for="task-user-7">
                                <span class="d-flex align-items-center">
                                <img alt="Kristina Van Der Stroem" src="assets/img/avatar-female-4.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Kristina Van Der Stroem</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-8">
                            <label class="custom-control-label" for="task-user-8">
                                <span class="d-flex align-items-center">
                                <img alt="David Whittaker" src="assets/img/avatar-male-4.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">David Whittaker</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-9">
                            <label class="custom-control-label" for="task-user-9">
                                <span class="d-flex align-items-center">
                                <img alt="Kerri-Anne Banks" src="assets/img/avatar-female-5.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Kerri-Anne Banks</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-10">
                            <label class="custom-control-label" for="task-user-10">
                                <span class="d-flex align-items-center">
                                <img alt="Masimba Sibanda" src="assets/img/avatar-male-5.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Masimba Sibanda</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-11">
                            <label class="custom-control-label" for="task-user-11">
                                <span class="d-flex align-items-center">
                                <img alt="Krishna Bajaj" src="assets/img/avatar-female-6.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Krishna Bajaj</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-12">
                            <label class="custom-control-label" for="task-user-12">
                                <span class="d-flex align-items-center">
                                <img alt="Kenny Tran" src="assets/img/avatar-male-6.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Kenny Tran</span>
                                </span>
                            </label>
                            </div>

                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                <!--end of modal body-->
                <div class="modal-footer">
                    <button role="button" class="btn btn-primary" type="submit">
                    Create Task
                    </button>
                </div>
                </div>
            </div>
            </form>

        </div>
    </div>
@endsection
