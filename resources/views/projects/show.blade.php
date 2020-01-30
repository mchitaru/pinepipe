@extends('layouts.app')

@php
    use Carbon\Carbon;
    use App\Project;

    $current_user=\Auth::user();
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
                <a class="dropdown-item" href="#">Mark as Complete</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-url="{{ route('projects.edit',$project->id) }}" data-ajax-popup="true" title="{{__('Edit Project')}}" data-toggle="tooltip">
                    {{__('Edit Project')}}
                </a>
            @endcan

            @can('client permission project')
                <a class="dropdown-item" href="#" data-url="{{ route('projects.client.permission',[$project->id,$project->client]) }}" data-ajax-popup="true" title="{{__('Edit Client Permission')}}" data-toggle="tooltip" data-size="lg">
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
        $days_remaining = $interval->format('%a')
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
                            <img alt="{{$user->name}}" {!! empty($user->avatar) ? "avatar='".$user->name."'" : "" !!} class="avatar" src="{{Storage::url($user->avatar)}}" data-filter-by="alt"/>
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
                <a class="nav-link" data-toggle="tab" href="#invoices" role="tab" aria-controls="timesheets" aria-selected="false">Invoices
                    <span class="badge badge-secondary">{{ count($invoices) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#expenses" role="tab" aria-controls="timesheets" aria-selected="false">Expenses
                    <span class="badge badge-secondary">{{ count($expenses) }}</span>
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
            <div class="tab-pane fade" id="timesheets" role="tabpanel" data-filter-list="card-list-body">
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
            <div class="tab-pane fade" id="invoices" role="tabpanel" data-filter-list="card-list-body">
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
            <div class="tab-pane fade" id="expenses" role="tabpanel" data-filter-list="card-list-body">
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
                                    <img alt="{{$current_user->name}}" {!! empty($current_user->avatar) ? "avatar='".$current_user->name."'" : "" !!} class="avatar" src="{{Storage::url($current_user->avatar)}}" data-filter-by="alt"/>
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
                                <a class="dropzone-delete dropdown-item text-danger" href="#" data-toggle="tooltip" title="{{__('Delete')}}" data-delete="Are You Sure?|This action can not be undone. Do you want to continue?">Delete</a>

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
            url: "{{route('projects.file.upload',[$project->id])}}",

            success: function (file, response) {
                if (response.is_success) {
                    toastrs('Success', 'File uploaded', 'success');
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
