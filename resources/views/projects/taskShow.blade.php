@extends('layouts.modal')

@php
    use Carbon\Carbon;
    use App\Projects;

    $current_user=\Auth::user();
    $profile=asset(Storage::url('avatar/'));
@endphp

@section('title')

<h5 class="modal-title">{{$task->title}} </h5>
<button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
<i class="material-icons">close</i>
</button>

@endsection

@section('content')

<form tabindex="-1" aria-hidden="true" data-remote="true">
@csrf
<div class="container-fluid">
     <div class="row justify-content-center">
        <div class="col">
            <div class="page-header pt-2">
            <p class="lead">{{$task->description}}</p>
            <div class="d-flex align-items-center">
                <ul class="avatars">

                <li>
                    <a href="#" data-toggle="tooltip" title="" data-original-title="{{(!empty($task->task_user)?$task->task_user->name:'')}}">
                        <img alt="{{(!empty($task->task_user)?$task->task_user->name:'')}}" class="avatar" src="{{(!empty($task->task_user->avatar)?$profile.'/'.$task->task_user->avatar:$profile.'/avatar.png')}}">
                    </a>
                </li>

                </ul>
            </div>
            <div>
                @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('create checklist',$perArr)))
                <div class="d-flex flex-row-reverse">
                    <small class="card-text" style="float:right;" id="taskProgressLabel">0%</small>
                </div>
                <div class="progress mt-0">
                    <div class="progress-bar bg-success" style="width:0%;" id="taskProgress"></div>
                </div>
                @endif

                <div class="d-flex justify-content-between text-small">
                <div class="d-flex align-items-center">
                    <i class="material-icons">playlist_add_check</i>
                    <span>3/7</span>
                </div>
                <span>{{__('Due') }} {{ Carbon::parse($task->due_date)->diffForHumans() }}</span>
                </div>
            </div>
            </div>
            <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#task" role="tab" aria-controls="task" aria-selected="true">Task</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tasknotes" role="tab" aria-controls="tasknotes" aria-selected="false">Notes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#taskfiles" role="tab" aria-controls="taskfiles" aria-selected="false">Files</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#taskactivity" role="tab" aria-controls="taskactivity" aria-selected="false">Activity</a>
            </li>
            </ul>
            <div class="tab-content">

            <div class="tab-pane fade show active" id="task" role="tabpanel">

                @can('create checklist')
                @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show checklist',$perArr)))

                <div class="content-list" data-filter-list="checklist">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>{{__('Checklist')}}</h3>
                    </div>
                    <form class="col-md-auto">
                    <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                        </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="Filter checklist" aria-label="Filter checklist">
                    </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    <form method="POST" id="form-checklist" data-action="{{ route('task.checklist.store',[$task->id]) }}">
                        @csrf
                        <div class="form-group row align-items-center">
                            <div class ="col">
                                <input type="text" name="name" class="form-control" required placeholder="{{__('Checklist Item')}}">
                            </div>
                            <div class ="col">
                                <button type="submit" class="btn btn-round" data-title={{__('Add')}} data-toggle="collapse" data-target="#form-checklist">
                                <i class="material-icons">add</i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <form class="checklist" id="checklist">

                    @foreach($task->taskCheckList as $checkList)

                    @can('create checklist')
                    @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('edit checklist',$perArr)))
                    <div class="row">
                        <div class="form-group col">
                        <span class="checklist-reorder">
                            <i class="material-icons">reorder</i>
                        </span>
                        <div class="custom-control custom-checkbox col">
                            <input type="checkbox" class="custom-control-input" id="checklist-{{$checkList->id}}" {{($checkList->status==1)?'checked':''}} value="{{$checkList->id}}" data-url="{{route('task.checklist.update',[$checkList->task_id,$checkList->id])}}">
                            <label class="custom-control-label" for="checklist-{{$checkList->id}}"></label>
                            <div>
                            <input type="text" placeholder="Checklist item" value="{{$checkList->name}}" data-filter-by="value" />
                            <div class="checklist-strikethrough"></div>
                            </div>
                        </div>
                        </div>
                        <!--end of form group-->
                    </div>
                    @endif
                    @endcan

                    @endforeach
                    </form>
                    <div class="drop-to-delete">
                    <div class="drag-to-delete-title">
                        <i class="material-icons">delete</i>
                    </div>
                    </div>
                </div>
                <!--end of content list body-->
                </div>
                <!--end of content list-->
                @endif
                @endcan
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show" id="tasknotes" role="tabpanel">

                <div class="content-list" data-filter-list="content-list-body">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>{{__('Notes')}}</h3>
                    </div>
                    <form class="col-md-auto">
                    <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                        </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="Filter notes" aria-label="Filter notes">
                    </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">

                    <form method="POST" id="form-comment" data-action="{{route('comment.store',[$task->project_id,$task->id])}}">
                        <div class="form-group row align-items-center">
                            <div class ="col-11">
                                <textarea class="form-control" name="comment" placeholder="{{ __('Write message')}}" id="example-textarea" rows="3" required></textarea>
                            </div>
                            <div class ="col-1">
                                <button type="button" class="btn btn-round" data-title={{__('Add')}}>
                                <i class="material-icons">add</i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div id="comments">
                    @foreach($task->comments as $comment)
                    <div class="card card-note">
                    <div class="card-header p-1">
                        <div class="media align-items-center">
                        <img alt="{{$comment->user->name}}" src="{{(!empty($comment->user->avatar)? $profile.'/'.$comment->user->avatar:$profile.'/avatar.png')}}" class="avatar" data-toggle="tooltip" data-title="{{$comment->user->name}}" data-filter-by="alt" />
                        <div class="media-body">
                            <h6 class="mb-0" data-filter-by="text">{{$comment->user->name}}</h6>
                        </div>
                        </div>
                        <div class="d-flex align-items-center">
                        <span data-filter-by="text">{{$comment->created_at->diffForHumans()}}</span>
                        <div class="ml-1 dropdown card-options">
                            <button class="btn-options" type="button" id="note-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Edit</a>
                                <a href="#" class="dropdown-item text-danger delete-comment" data-url="{{route('comment.destroy',[$comment->id])}}">
                                    {{__('Delete')}}
                                </a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="card-body p-1" data-filter-by="text">
                        {{$comment->comment}}
                    </div>
                    </div>
                    @endforeach
                    </div>
                </div>
                </div>
            </div>
            <!--end of tab-->
            <div class="tab-pane fade" id="taskfiles" role="tabpanel" data-filter-list="dropzone-previews">
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
                <div class="content-list-body">
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
                            <a href="#" class="dz-filename dropzone-file">
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
                                <a class="dropdown-item dropzone-file" href="#">Download</a>
                                <a class="dropdown-item dropzone-file" href="#">Share</a>
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
                    <form class="dropzone" id="my-task-dropzone">
                        <span class="dz-message">Drop files here or click here to upload</span>
                    </form>

                    <ul class="list-group list-group-activity dropzone-previews flex-column-reverse">
                    </ul>
                </div>
                </div>
                <!--end of content list-->
            </div>
            <div class="tab-pane fade" id="taskactivity" role="tabpanel" data-filter-list="list-group-activity">
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

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">edit</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Peggy" src="assets/img/avatar-female-2.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Peggy</span>
                            <span data-filter-by="text">added the note</span><a href="#" data-filter-by="text">Client Meeting Notes</a>
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
                                <i class="material-icons">edit</i>
                            </div>
                            </li>
                            <li>
                            <img alt="David" src="assets/img/avatar-male-4.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">David</span>
                            <span data-filter-by="text">added the note</span><a href="#" data-filter-by="text">Aesthetic note</a>
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
                            <img alt="Marcus" src="assets/img/avatar-male-1.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Marcus</span>
                            <span data-filter-by="text">was assigned to the task</span>
                            </div>
                            <span class="text-small" data-filter-by="text">4 days ago</span>
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
                            <span data-filter-by="text">was assigned to the task</span>
                            </div>
                            <span class="text-small" data-filter-by="text">5 days ago</span>
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
                            <img alt="Claire" src="assets/img/avatar-female-1.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Claire</span>
                            <span data-filter-by="text">added to the task checklist</span>
                            </div>
                            <span class="text-small" data-filter-by="text">5 days ago</span>
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
                            <span data-filter-by="text">started the task</span>
                            </div>
                            <span class="text-small" data-filter-by="text">6 days ago</span>
                        </div>
                        </div>
                    </li>

                    </ol>
                </div>
                </div>
                <!--end of content list-->
            </div>
            </div>
            <form class="modal fade" id="task-edit-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Task</h5>
                    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
                    <i class="material-icons">close</i>
                    </button>
                </div>
                <!--end of modal head-->
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" id="task-edit-details-tab" data-toggle="tab" href="#task-edit-details" role="tab" aria-controls="task-edit-details" aria-selected="true">Details</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="task-edit-members-tab" data-toggle="tab" href="#task-edit-members" role="tab" aria-controls="task-edit-members" aria-selected="false">Members</a>
                    </li>
                </ul>
                <div class="modal-body">
                    <div class="tab-content">
                    <div class="tab-pane fade show active" id="task-edit-details" role="tabpanel">
                        <h6>General Details</h6>
                        <div class="form-group row align-items-center">
                        <label class="col-3">Name</label>
                        <input class="form-control col" type="text" placeholder="Task name" value="Create brand mood boards" name="task-name" />
                        </div>
                        <div class="form-group row">
                        <label class="col-3">Description</label>
                        <textarea class="form-control col" rows="3" placeholder="Task description" name="task-description">Assemble three distinct mood boards for client consideration</textarea>
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
                    <div class="tab-pane fade" id="task-edit-members" role="tabpanel">
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
                    Save
                    </button>
                </div>
                </div>
            </div>
            </form>

            <form  method="post" enctype="multipart/form-data" class="modal fade" id="note-add-modal" tabindex="-1" aria-hidden="true" action="{{route('comment.store',[$task->project_id,$task->id])}}">
                @csrf
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Note</h5>
                        <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
                        <i class="material-icons">close</i>
                        </button>
                    </div>
                    <!--end of modal head-->
                    <div class="modal-body">
                        <div class="form-group row align-items-center">
                        <label class="col-3">Title</label>
                        <input class="form-control col" type="text" placeholder="Note title" name="note-name" />
                        </div>
                        <div class="form-group row">
                        <label class="col-3">Text</label>
                        <textarea class="form-control col" rows="6" placeholder="Body text for note" name="comment"></textarea>
                        </div>
                    </div>
                    <!--end of modal body-->
                    <div class="modal-footer">
                        <button role="button" class="btn btn-primary" type="submit">
                        {{__('Create Note')}}
                        </button>
                    </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</form>
@endsection

@section('footer')
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
@endsection

<script>

    $(document).on("change", "#checklist input[type=checkbox]", function () {
            $.ajax({
                url: $(this).attr('data-url'),
                type: 'PUT',
                data: {_token: $('meta[name="csrf-token"]').attr('content')},
                // dataType: 'JSON',
                success: function (data) {
                    toastrs('Success', '{{ __("Checklist Updated Successfully!")}}', 'success');
                    // console.log(data);
                },
                error: function (data) {
                    data = data.responseJSON;
                    toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                }
            });
            taskCheckbox();
    });

    $(document).on('submit', '#form-checklist', function (e) {
            e.preventDefault();

            $.ajax({
                url: $("#form-checklist").data('action'),
                type: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    toastrs('Success', '{{ __("Checklist Added Successfully!")}}', 'success');

                    var html =  '<div class="row">' +
                                    '<div class="form-group col">' +
                                        '<span class="checklist-reorder">' +
                                            '<i class="material-icons">reorder</i>' +
                                        '</span>' +
                                        '<div class="custom-control custom-checkbox col">' +
                                            '<input type="checkbox" class="custom-control-input" id="checklist-' + data.id + '" value="' + data.id + '" data-url="' + data.updateUrl + '">' +
                                            '<label class="custom-control-label" for="checklist-' + data.id + '"></label>' +
                                            '<div>' +
                                                '<input type="text" placeholder="Checklist item" value="' + data.name + '" data-filter-by="value" />' +
                                                '<div class="checklist-strikethrough"></div>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>';

                    $("#checklist").prepend(html);
                    $("#form-checklist input[name=name]").val('');
                    $("#form-checklist").collapse('toggle');
                },
            });
    });

    $(document).on('click', '#form-comment button', function (e) {
            var comment = $.trim($("#form-comment textarea[name='comment']").val());
            var name='{{\Auth::user()->name}}';
            if (comment != '') {
                $.ajax({
                    url: $("#form-comment").data('action'),
                    data: {comment: comment, "_token": $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    success: function (data) {
                        data = JSON.parse(data);


                    var html = '<div class="card card-note">'+
                                    '<div class="card-header p-1">'+
                                        '<div class="media align-items-center">'+
                                        '<img alt="{{\Auth::user()->name}}" src="{{(!empty(\Auth::user()->avatar)? $profile.'/'.\Auth::user()->avatar:$profile.'/avatar.png')}}" class="avatar" data-toggle="tooltip" data-title="{{\Auth::user()->name}}" data-filter-by="alt" />'+
                                        '<div class="media-body">'+
                                            '<h6 class="mb-0" data-filter-by="text">{{\Auth::user()->name}}</h6>'+
                                        '</div>'+
                                        '</div>'+
                                        '<div class="d-flex align-items-center">'+
                                        '<span data-filter-by="text">{{Carbon::now()->diffForHumans()}}</span>'+
                                        '<div class="ml-1 dropdown card-options">'+
                                            '<button class="btn-options" type="button" id="note-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                            '<i class="material-icons">more_vert</i>'+
                                            '</button>'+
                                            '<div class="dropdown-menu dropdown-menu-right">'+
                                                '<a class="dropdown-item" href="#">Edit</a>'+
                                                '<a href="#" class="dropdown-item text-danger delete-comment" data-url="' + data.deleteUrl + '" > {{__('Delete')}}'+
                                                '</a>'+
                                            '</div>'+
                                        '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="card-body p-1" data-filter-by="text">'+
                                        data.comment+
                                    '</div>'+
                                '</div>';

                        $("#comments").prepend(html);
                        $("#form-comment textarea[name='comment']").val('');
                        toastrs('Success', '{{ __("Comment Added Successfully!")}}', 'success');
                    },
                    error: function (data) {
                        toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                    }
                });
            } else {
                toastrs('Error', '{{ __("Please write comment!")}}', 'error');
            }
        });

    $(document).on("click", ".delete-comment", function () {
        if (confirm('Are You Sure ?')) {
            var btn = $(this);
            $.ajax({
                url: $(this).attr('data-url'),
                type: 'DELETE',
                data: {_token: $('meta[name="csrf-token"]').attr('content')},
                dataType: 'JSON',
                success: function (data) {
                    toastrs('Success', '{{ __("Comment Deleted Successfully!")}}', 'success');
                    btn.closest('.card-note').remove();
                },
                error: function (data) {
                    data = data.responseJSON;
                    if (data.message) {
                        toastrs('Error', data.message, 'error');
                    } else {
                        toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                    }
                }
            });
        }
    });

    Dropzone.autoDiscover = false;
    myDropzone = new Dropzone("#my-task-dropzone", {
        previewTemplate: document.querySelector('.dz-template').innerHTML,
        thumbnailWidth: 320,
        thumbnailHeight: 320,
        thumbnailMethod: "contain",
        previewsContainer: ".dropzone-previews",
        maxFiles: 20,
        maxFilesize: 2,
        parallelUploads: 1,
        acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
        url: "{{route('task.file.upload',[$task->id])}}",

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
            formData.append("task_id", {{$task->id}});
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
        $files = $task->taskFiles;
    @endphp

    @foreach($files as $file)
    var mockFile = {name: "{{$file->name}}", size: {{filesize(storage_path('app/public/tasks/'.$file->file))}} };
    myDropzone.emit("addedfile", mockFile);
    myDropzone.emit("processing", mockFile);
    myDropzone.emit("thumbnail", mockFile, "{{asset('app/public/tasks/'.$file->file)}}");
    myDropzone.emit("complete", mockFile);

    dropzoneBtn(mockFile, {download: "{{route('task.file.download',[$task->id,$file->id])}}", delete: "{{route('task.file.delete',[$task->id,$file->id])}}"});
    @endforeach

</script>
