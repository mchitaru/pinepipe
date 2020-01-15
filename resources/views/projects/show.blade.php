@extends('layouts.app')

@php
    use Carbon\Carbon;
    use App\Projects;

    $current_user=\Auth::user();
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

    var hash = window.location.hash ? window.location.hash : '#tasks';

    $('.nav-tabs a[href="' + hash + '"]').tab('show');

});

</script>

@endpush

@section('page-title')
    {{__('Project Details')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ route('projects.index') }}">{{__('Projects')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{$project->name}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
          <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            @if(Gate::check('edit project') || Gate::check('delete project'))

            @can('edit project')
                <a class="dropdown-item" href="#" data-url="{{ route('projects.edit',$project->id) }}" data-ajax-popup="true" data-title="{{__('Edit Project')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                    {{__('Edit Project')}}
                </a>
            @endcan
            <a class="dropdown-item" href="#">Mark as Complete</a>
            @can('manage task')
            @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show task',$perArr)))
                <a class="dropdown-item" href="{{ route('project.taskboard',$project->id) }}" data-ajax-popup="true" data-title="{{__('Task Kanban')}}" data-toggle="tooltip" data-original-title="{{__('Task Kanban')}}">
                    {{__('Kanban Board')}}
                </a>
            @endif
            @endcan
            <a class="dropdown-item" href="#">Share</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Archive</a>
            @can('delete project')
                <a class="dropdown-item text-danger" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$project->id}}').submit();">
                    {{__('Delete')}}
                </a>
                {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id],'id'=>'delete-form-'.$project->id]) !!}
                {!! Form::close() !!}
            @endcan

            @endif
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

        $label = $project->getProgressColor($percentage);

        $datetime1 = new DateTime($project->due_date);
        $datetime2 = new DateTime(date('Y-m-d'));
        $interval = $datetime1->diff($datetime2);
        $days_remaining = $interval->format('%a')
    @endphp

    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h1>{{$project->name}}</h1>
                    <div class="pl-2">
                        <a href="{{ route('users.index',$project->client()->id) }}" data-toggle="tooltip">
                            <span class="badge badge-secondary">{{ (!empty($project->client())?$project->client()->name:'') }}</span>
                        </a>
                    </div>
                </div>
            <p class="lead">{{ $project->description }}</p>
            <div class="d-flex align-items-center">
                <ul class="avatars">
                    <li>
                        <a href="{{ route('users.index',$project->client()->id) }}" data-toggle="tooltip" data-original-title="{{ (!empty($project->client())?$project->client()->name:'') }}">
                            <img class="avatar" src="{{(!empty($project->client()->avatar)? $profile.'/'.$project->client()->avatar:$profile.'/avatar.png')}}" data-filter-by="alt" />
                        </a>
                    </li>

                    @foreach($project->project_user() as $user)
                    <li>
                        <a href="{{ route('users.index',$user->id) }}" data-toggle="tooltip" data-original-title="{{$user->name}}">
                            <img alt="{{$user->name}}" class="avatar" src="{{(!empty($user->avatar)? $profile.'/'.$user->avatar:$profile.'/avatar.png')}}" data-filter-by="alt" />
                        </a>
                    </li>
                    @endforeach

                </ul>

                @can('invite user project')

                <button class="btn btn-round" data-url="{{ route('project.invite',$project->id) }}" data-ajax-popup="true" data-title="{{__('Add User')}}">
                    <i class="material-icons">add</i>
                </button>
                @endcan

            </div>
            <div>
                <div class="d-flex flex-row-reverse">
                    <small class="card-text" style="float:right;">{{$percentage}}%</small>
                </div>
                <div class="progress mt-0">
                        <div class="progress-bar bg-success" style="width:{{$percentage}}%;"></div>
                </div>
                <div class="d-flex justify-content-between text-small">
                <div class="d-flex align-items-center">
                    <i class="material-icons">done</i>
                    <span data-toggle="tooltip" data-original-title="{{__('Status')}}">{{$project_status}}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="material-icons">playlist_add_check</i>
                    <span data-toggle="tooltip" data-original-title="{{__('Completed Tasks')}}">{{$completed_task}}/{{$total_task}}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="material-icons">people</i>
                    <span data-toggle="tooltip" data-original-title="{{__('Members')}}">{{$project->project_user()->count()+1}}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="material-icons">calendar_today</i>
                    <span data-toggle="tooltip" data-original-title="{{__('Days Remaining')}}">{{$days_remaining}}</span>
                </div>
                <span>{{__('Due') }} {{ \Auth::user()->dateFormat($project->due_date) }}</span>
                </div>
            </div>
            </div>
            <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tasks" role="tab" aria-controls="tasks" aria-selected="true">Tasks
                    <span class="badge badge-secondary">{{ $total_task }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#timesheets" role="tab" aria-controls="timesheets" aria-selected="false">Timesheets
                    <span class="badge badge-secondary">{{ count($timeSheets) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false">Files</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">Activity</a>
            </li>
            </ul>
            <div class="tab-content">
            <div class="tab-pane fade show" id="tasks" role="tabpanel" data-filter-list="card-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Tasks')}}</h3>

                    <button class="btn btn-round" data-url="{{ route('task.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Add')}}" data-toggle="tooltip" data-original-title="{{__('Add')}}">
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

                @foreach($stages as $stage)

                @if(\Auth::user()->type =='client' || \Auth::user()->type =='company')
                    @php $tasks =$stage->tasks($project->id)    @endphp
                @else
                    @php $tasks =$stage->tasks($project->id)    @endphp
                @endif

                    <div class="card-list">
                        <div class="card-list-head">
                        <h6>{{$stage->name}} ({{ count($tasks) }})</h6>
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

                        @foreach($tasks as $task)

                        @php
                            $total_subtask = $task->taskTotalCheckListCount();
                            $completed_subtask = $task->taskCompleteCheckListCount();
                            
                            $task_percentage=0;
                            if($total_subtask!=0){
                                $task_percentage = intval(($completed_subtask / $total_subtask) * 100);
                            }

                            $label = Projects::getProgressColor($task_percentage);
                        @endphp

                            <div class="card card-task">
                                <div class="progress">
                                <div class="progress-bar {{$label}}" role="progressbar" style="width: {{$task_percentage}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="card-body">
                                <div class="card-title">
                                    <a data-url="{{ route('task.show',$task->id) }}" data-ajax-popup="true"  data-size="lg" class="text-body">
                                        <h6 data-filter-by="text">{{$task->title}}</h6>
                                    </a>
                                    <span class="badge badge-secondary">
                                        @if($task->priority =='low')
                                                <div class="label label-soft-success"> {{ $task->priority }}</div>
                                            @elseif($task->priority =='medium')
                                                <div class="label label-soft-warning"> {{ $task->priority }}</div>
                                            @elseif($task->priority =='high')
                                                <div class="label label-soft-danger"> {{ $task->priority }}</div>
                                            @endif
                                     </span>

                                     <p>
                                        <span class="text-small">{{ Carbon::parse($task->due_date)->diffForHumans() }}</span>
                                    </p>
                                </div>
                                <div class="card-meta">
                                    <ul class="avatars">

                                    <li>
                                        <a href="#" data-toggle="tooltip" title="" data-original-title="{{(!empty($task->task_user)?$task->task_user->name:'')}}">
                                            <img alt="{{(!empty($task->task_user)?$task->task_user->name:'')}}" class="avatar" src="{{(!empty($task->task_user->avatar)?$profile.'/'.$task->task_user->avatar:$profile.'/avatar.png')}}">
                                        </a>
                                    </li>

                                    </ul>
                                    <div class="d-flex align-items-center">
                                    <i class="material-icons">playlist_add_check</i>
                                    <p class="small @if($total_subtask==0) text-muted @endif @if($completed_subtask==$total_subtask && $completed_subtask!=0) text-success @else text-danger @endif">
                                        <span>{{$completed_subtask}}/{{$total_subtask}}</span>
                                    </p>
                                    </div>
                                    @if(Gate::check('edit task') || Gate::check('delete task'))
                                        <div class="dropdown card-options">
                                        <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">

                                            <a class="dropdown-item" href="#">Mark as done</a>
                                            @can('edit task')
                                                <a href="#" class="dropdown-item" data-url="{{ route('task.edit',$task->id) }}" data-ajax-popup="true" data-title="{{__('Edit')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    {{__('Edit')}}
                                                </a>
                                            @endcan
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#">Archive</a>
                                            @can('delete task')
                                                <a href="#" class="dropdown-item text-danger" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$task->id}}').submit();">
                                                    {{__('Delete')}}
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['task.destroy', $task->id],'id'=>'delete-form-'.$task->id]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @endforeach

                        </div>
                    </div>
                @endforeach
                <!--end of content list body-->
                </div>
                <!--end of content list-->
            </div>
            <!--end of tab-->
            <div class="tab-pane fade" id="timesheets" role="tabpanel" data-filter-list="card-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Timesheets')}}</h3>

                    <button class="btn btn-round" data-url="{{ route('task.timesheet',$project->id) }}" data-ajax-popup="true" data-title="{{__('Add')}}" data-toggle="tooltip" data-original-title="{{__('Add')}}">
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
                    <input type="search" class="form-control filter-list-input" placeholder="Filter Timesheets" aria-label="Filter Timesheets">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    @foreach($timeSheets as $timeSheet)

                        <div class="card card-task mb-1">
                            <div class="card-body p-2">
                            <div class="card-title col-xs-12 col-sm-3">
                                <h6 data-filter-by="text">{{ Auth::user()->dateFormat($timeSheet->date) }}</h6>
                                <p>
                                    <span class="text-small">{{ !empty($timeSheet->task())? $timeSheet->task()->title : ''}}</span>
                                </p>
                            </div>
                            <div class="card-title col-xs-12 col-sm-5">
                                <div class="container row align-items-center">
                                    <i class="material-icons">access_time</i>
                                    <span class="text-small" data-filter-by="text">{{ $timeSheet->hours }}h</span>
                                </div>
                                <div class="container row align-items-center">
                                    <i class="material-icons">note</i>
                                    <span class="text-small" data-filter-by="text">{{ $timeSheet->remark }}</span>
                                </div>
                            </div>
                            <div class="card-meta">
                                <ul class="avatars">

                                <li>
                                    <a href="#" data-toggle="tooltip" title="" data-original-title="{{(!empty($timeSheet->user())?$timeSheet->user()->name:'')}}">
                                        <img alt="{{(!empty($timeSheet->user())?$timeSheet->user()->name:'')}}" class="avatar" src="{{(!empty($timeSheet->user()->avatar)?$profile.'/'.$timeSheet->user()->avatar:$profile.'/avatar.png')}}">
                                    </a>
                                </li>

                                </ul>
                                @if(\Auth::user()->type!='client')
                                    <div class="dropdown card-options">
                                    <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">

                                        <a href="#" class="dropdown-item" data-url="{{ route('task.timesheet.edit',[$project->id,$timeSheet->id]) }}" data-ajax-popup="true" data-title="{{__('Edit')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                            {{__('Edit')}}
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="#">Archive</a>
                                        <a href="#" class="dropdown-item text-danger" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$timeSheet->id}}').submit();">
                                            {{__('Delete')}}
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['task.timesheet.destroy', $project->id,$timeSheet->id],'id'=>'delete-form-'.$timeSheet->id]) !!}
                                        {!! Form::close() !!}    
                                    </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
            <!--end of tab-->
            @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show uploading',$perArr)))
            <div class="tab-pane fade" id="files" role="tabpanel" data-filter-list="dropzone-previews">
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
                                <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img alt="{{$current_user->name}}" title="{{$current_user->name}}" src="{{(!empty($current_user->avatar)? $profile.'/'.$current_user->avatar : $profile.'/avatar.png')}}" class="avatar" />
                                </a>
                            </li>
                            </ul>
                            <div class="media-body d-flex justify-content-between align-items-center">
                            <div class="dz-file-details">
                                <a href="#" class="dropzone-file dz-filename">
                                <span data-dz-name></span>
                                </a>
                                <br>
                                <span class="text-small dz-size" data-dz-size></span>
                            </div>
                            <img alt="Loader" src="{{ asset('assets/img/loader.svg') }}" class="dz-loading" />
                            <div class="dropdown">
                                <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropzone-file dropdown-item" href="#">Download</a>
                                <a class="dropzone-file dropdown-item" href="#">Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropzone-delete dropdown-item text-danger" href="#" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-delete="Are You Sure?|This action can not be undone. Do you want to continue?">Delete</a>

                                </div>
                            </div>
                            <button class="btn btn-danger btn-sm dz-remove" data-dz-remove>
                                {{__('Cancel')}}
                            </button>
                            </div>
                        </div>
                        <div class="progress dz-progress">
                            <div class="progress-bar dz-upload" data-dz-uploadprogress></div>
                        </div>
                        </li>
                    </ul>

                    <form class="dropzone" id="my-dropzone">
                        <span class="dz-message">Drop files here or click here to upload</span>
                    </form>

                    <ul class="list-group list-group-activity dropzone-previews flex-column-reverse">
                    </ul>
                    </div>
                </div>
                </div>
                <!--end of content list-->
            </div>
            @endif
            @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show activity',$perArr)))
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
                        <input type="search" class="form-control filter-list-input" placeholder="Filter activity" aria-label="Filter activity">
                    </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    <ol class="list-group list-group-activity">

                    @foreach($project->activities as $activity)
                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">playlist_add_check</i>
                            </div>
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">{{$activity->log_type}}</span>
                            <span data-filter-by="text"> {!! $activity->remark !!}</span>
                            </div>
                            <span class="text-small" data-filter-by="text">{{$activity->created_at->diffforhumans()}}</span>
                        </div>
                        </div>
                    </li>
                    @endforeach

                    </ol>
                </div>
                </div>
                <!--end of content list-->
            </div>
            @endif
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#my-dropzone", {
            previewTemplate: document.querySelector('.dz-template').innerHTML,
            thumbnailWidth: 320,
            thumbnailHeight: 320,
            thumbnailMethod: "contain",
            previewsContainer: ".dropzone-previews",
            maxFiles: 20,
            maxFilesize: 2,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{route('project.file.upload',[$project->id])}}",

            success: function (file, response) {
                if (response.is_success) {
                    dropzoneBtn(file, response);
                } else {
                    this.removeFile(file);
                    toastrs('Error', response.error, 'error');
                }
            },
            error: function (file, response) {
                this.removeFile(file);
                if (response.error) {
                    toastrs('Error', response.error, 'error');
                } else {
                    toastrs('Error', response.error, 'error');
                }
            },
            sending: function(file, xhr, formData) {
                formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
                formData.append("project_id", {{$project->id}});
            },
        });

        function deleteDropzoneFile(btn) {

            $.ajax({
                url: btn.attr('href'),
                data: {_token: $('meta[name="csrf-token"]').attr('content')},
                type: 'DELETE',
                success: function (response) {
                    if (response.is_success) {
                        btn.closest('.list-group-item').remove();
                    } else {
                        toastrs('Error', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        toastrs('Error', response.error, 'error');
                    } else {
                        toastrs('Error', response.error, 'error');
                    }
                }
            });
        }

        function dropzoneBtn(file, response) {

            $( ".dropzone-file", $(".dz-preview").last() ).each(function() {
                $(this).attr("href", response.download);
            });

            $('[data-delete]', $(".dz-preview").last()).each(function() {

                $(this).attr("href", response.delete);

                var me = $(this),
                    me_data = me.data('delete');

                me_data = me_data.split("|");

                me.fireModal({
                title: me_data[0],
                body: me_data[1],
                buttons: [
                    {
                    text: me.data('confirm-text-yes') || 'Yes',
                    class: 'btn btn-danger btn-shadow',
                    handler: function(modal) {
                        deleteDropzoneFile(me);
                        $.destroyModal(modal);
                    }
                    },
                    {
                    text: me.data('confirm-text-cancel') || 'Cancel',
                    class: 'btn btn-secondary',
                    handler: function(modal) {
                        $.destroyModal(modal);
                    }
                    }
                ]
                })
            });
        }

        @php
            $files = $project->files;
        @endphp

        @foreach($files as $file)
        var mockFile = {name: "{{$file->file_name}}", size: {{filesize(storage_path('app/public/project_files/'.$file->file_path))}} };
        myDropzone.emit("addedfile", mockFile);
        myDropzone.emit("processing", mockFile);
        myDropzone.emit("thumbnail", mockFile, "{{asset('app/public/project_files/'.$file->file_path)}}");
        myDropzone.emit("complete", mockFile);

        dropzoneBtn(mockFile, {download: "{{route('projects.file.download',[$project->id,$file->id])}}", delete: "{{route('projects.file.delete',[$project->id,$file->id])}}"});
        @endforeach

    </script>
@endpush
