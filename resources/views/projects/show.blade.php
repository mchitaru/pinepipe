@extends('layouts.app')

@php
    use Carbon\Carbon;
    use App\Project;
    use App\Http\Helpers;

    $current_user=\Auth::user();
@endphp

@push('stylesheets')
@endpush

@push('scripts')

<script>

    // keep active tab
    $(document).ready(function() {

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) 
        {
            window.history.replaceState(null, null, $(e.target).attr('href'));
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
                <a class="dropdown-item disabled" href="#">Mark as Complete</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('projects.edit', $project->id) }}" data-remote="true" data-type="text">
                    {{__('Edit Project')}}
                </a>
            @endcan

            @can('client permission project')
                <a class="dropdown-item" href="{{ route('projects.client.permission',[$project->id,$project->client]) }}" data-remote="true" data-type="text">
                    {{__('Edit Client Permission')}}
                </a>
            @endcan

            <div class="dropdown-divider"></div>
            @can('manage task')
            @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show task',$perArr)))
                <a class="dropdown-item" href="{{ route('projects.task.board',$project->id) }}" data-title="{{__('Task Board')}}">
                    {{__('Task Board')}}
                </a>
            @endif
            @endcan
            <a class="dropdown-item" href="#">Share</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Archive</a>
            @can('delete project')
                <a class="dropdown-item text-danger" href="{{ route('projects.destroy', $project->id) }}" data-method="delete" data-remote="true" data-type="text">
                    {{__('Delete')}}
                </a>
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
        $days_remaining = $interval->format('%a');
        $dz_id = 'project-files-dz';
    @endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h1>{{$project->name}}</h1>
                    <div class="pl-2">
                        <a href="{{ route('users.index',$project->client->id) }}" data-toggle="tooltip">
                            <span class="badge badge-secondary">{{ (!empty($project->client)?$project->client->name:'') }}</span>
                        </a>
                    </div>
                </div>
            <p class="lead">{{ $project->description }}</p>
            <div class="d-flex align-items-center">
                <ul class="avatars">

                    @foreach($project->users as $user)
                    <li>
                        <a href="{{ route('users.index',$user->id) }}" data-toggle="tooltip" title="{{$user->name}}">
                            {!!Helpers::buildAvatar($user)!!}
                        </a>
                    </li>
                    @endforeach

                </ul>

                @can('invite user project')

                <a href="{{ route('project.invite', $project->id)  }}" class="btn btn-round" data-remote="true" data-type="text" data-toggle="tooltip" title="{{__('Invite Users')}}">
                    <i class="material-icons">add</i>
                </a>
                @endcan

            </div>
            <div>
                <div class="d-flex flex-row-reverse">
                    <small class="card-text" style="float:right;">{{$percentage}}%</small>
                </div>
                <div class="progress mt-0">
                        <div class="progress-bar {{$label}}" style="width:{{$percentage}}%;"></div>
                </div>
                <div class="d-flex justify-content-between text-small">
                <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Status')}}">
                    <i class="material-icons">done</i>
                    <span>{{$project_status}}</span>
                </div>
                <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Completed Tasks')}}">
                    <i class="material-icons">playlist_add_check</i>
                    <span>{{$completed_task}}/{{$total_task}}</span>
                </div>
                <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Members')}}">
                    <i class="material-icons">people</i>
                    <span>{{$project->users()->count()+1}}</span>
                </div>
                <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Days Remaining')}}">
                    <i class="material-icons">calendar_today</i>
                    <span>{{$days_remaining}}</span>
                </div>
                <span>{{__('Due') }} {{ \Auth::user()->dateFormat($project->due_date) }}</span>
                </div>
            </div>
            </div>
            <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tasks" role="tab" aria-controls="tasks" aria-selected="true">Tasks
                    <span class="badge badge-secondary">{{ $task_count }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#timesheets" role="tab" aria-controls="timesheets" aria-selected="false">Timesheets
                    <span class="badge badge-secondary">{{ count($timesheets) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#invoices" role="tab" aria-controls="invoices" aria-selected="false">Invoices
                    <span class="badge badge-secondary">{{ count($invoices) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#expenses" role="tab" aria-controls="expenses" aria-selected="false">Expenses
                    <span class="badge badge-secondary">{{ count($expenses) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#project-files" role="tab" aria-controls="project-files" aria-selected="false">Files
                    <span class="badge badge-secondary">{{ $project->files->count() }}</span>
                </a>
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

                    <a href="{{ route('projects.task.create', $project->id)  }}" class="btn btn-round" data-remote="true" data-type="text" >
                        <i class="material-icons">add</i>
                    </a>

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

                    @include('tasks.index')
                    
                <!--end of content list body-->
                </div>
                <!--end of content list-->
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show" id="timesheets" role="tabpanel" data-filter-list="card-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Timesheets')}}</h3>

                    <a href="{{ route('projects.timesheet.create', $project->id)  }}" class="btn btn-round" data-remote="true" data-type="text" >
                        <i class="material-icons">add</i>
                    </a>
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

                    @include('timesheets.index')
                </div>
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show" id="invoices" role="tabpanel" data-filter-list="card-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Invoices')}}</h3>

                    <a href="{{ route('projects.invoice.create', $project->id)  }}" class="btn btn-round" data-remote="true" data-type="text" >
                        <i class="material-icons">add</i>
                    </a>
                </div>
                <form class="col-md-auto">
                    <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="material-icons">filter_list</i>
                        </span>
                    </div>
                    <input type="search" class="form-control filter-list-input" placeholder="Filter Invoices" aria-label="Filter Invoices">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">

                    @include('invoices.index')
                </div>
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show" id="expenses" role="tabpanel" data-filter-list="card-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Expenses')}}</h3>

                    <a href="{{ route('projects.expense.create', $project->id)  }}" class="btn btn-round" data-remote="true" data-type="text" >
                        <i class="material-icons">add</i>
                    </a>
                </div>
                <form class="col-md-auto">
                    <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="material-icons">filter_list</i>
                        </span>
                    </div>
                    <input type="search" class="form-control filter-list-input" placeholder="Filter Expenses" aria-label="Filter Expenses">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">

                    @include('expenses.index')
                </div>
            </div>
            <!--end of tab-->
            @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show uploading',$perArr)))
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
                            <input type="search" class="form-control filter-list-input" placeholder="Filter files" aria-label="Filter Tasks">
                        </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body row">

                    @include('files.index')

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
                    @include('activity.index')
                </div>
                </div>
                <!--end of content list-->
            </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    
    dzProject = $('#{{$dz_id}}').dropzone({
        previewTemplate: document.querySelector('.dz-template').innerHTML,
        createImageThumbnails: false,
        previewsContainer: "#{{$dz_id}}-previews",
        maxFiles: 20,
        maxFilesize: 2,
        parallelUploads: 1,
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.svg,.pdf,.txt,.doc,.docx,.zip,.rar",
        url: "{{route('projects.file.upload',[$project->id])}}",

        success: function (file, response) {
            if (response.is_success) {
                toastrs('File uploaded', 'success');
                dropzoneBtn(file, response);
                LetterAvatar.transform();
            } else {
                this.removeFile(file);
                toastrs(response.error, 'error');
            }
        },
        error: function (file, response) {
            this.removeFile(file);
            if (response.error) {
                toastrs(response.error, 'error');
            } else {
                toastrs(response.error, 'error');
            }
        },
        sending: function(file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("project_id", {{$project->id}});
        },
    })[0];

    function deleteDropzoneFile(btn) {

        $.ajax({
            url: btn.attr('href'),
            data: {_token: $('meta[name="csrf-token"]').attr('content')},
            type: 'DELETE',
            success: function (response) {
                if (response.is_success) {
                    btn.closest('.list-group-item').remove();
                } else {
                    toastrs(response.error, 'error');
                }
            },
            error: function (response) {
                response = response.responseJSON;
                if (response.is_success) {
                    toastrs(response.error, 'error');
                } else {
                    toastrs(response.error, 'error');
                }
            }
        });
    }

    function dropzoneBtn(file, response) {

        $( ".dropzone-file", $(".dz-preview").last() ).each(function() {
            $(this).attr("href", response.download);
        });

        $( ".dropzone-delete", $(".dz-preview").last() ).each(function() {
            $(this).attr("href", response.delete);
        });
    }

    @php
        $files = $project->files;
    @endphp

    @foreach($files as $file)
        var mockFile = {name: "{{$file->file_name}}", size: {{filesize(storage_path('app/'.$file->file_path))}} };
        dzProject.dropzone.emit("addedfile", mockFile);
        dzProject.dropzone.emit("processing", mockFile);
        dzProject.dropzone.emit("complete", mockFile);

        dropzoneBtn(mockFile, {download: "{{route('projects.file.download',[$project->id,$file->id])}}", delete: "{{route('projects.file.delete',[$project->id,$file->id])}}"});
    @endforeach

</script>
@endpush
