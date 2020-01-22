
@extends('layouts.modal')

@php
use Carbon\Carbon;
use App\Project;

$current_user=\Auth::user();
$profile=asset(Storage::url('avatar/'));


$total_task = $task->taskTotalCheckListCount();
$completed_task=$task->taskCompleteCheckListCount();

$percentage=0;
if($total_task!=0){
    $percentage = intval(($completed_task / $total_task) * 100);
}

$label = $task->getProgressColor($percentage);
@endphp

@section('title')
{{$task->title}}
@endsection

@section('content')

{{-- <div class="modal-body container-fluid"> --}}
 <div class="row justify-content-center" data-remote="true">
    <div class="col">
        <div class="page-header pt-2">
        <p class="lead">{{$task->description}}</p>
        <div class="d-flex align-items-center">
            <ul class="avatars">

            <li>
                @if(!empty($task->task_user))
                <a href="#" data-toggle="tooltip" title="" data-original-title="{{(!empty($task->task_user)?$task->task_user->name:'')}}">
                    <img alt="{{$task->task_user->name}}" {!! empty($task->task_user->avatar) ? "avatar='".$task->task_user->name."'" : "" !!} class="avatar" src="{{asset(Storage::url("avatar/".$task->task_user->avatar))}}" data-filter-by="alt"/>
                </a>
                @endif
            </li>

            </ul>
        </div>
        <div>
            @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('create checklist',$perArr)))
            <div class="d-flex flex-row-reverse">
                <small class="card-text" style="float:right;" >{{$percentage}}%</small>
            </div>
            <div class="progress mt-0">
            <div class="progress-bar {{$label}}" style="width:{{$percentage}}%;"></div>
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
            <a class="nav-link {{(empty(request()->segment(3)) || request()->segment(3)=='checklist')?'active':''}}" data-toggle="tab" href="#task" role="tab" aria-controls="task" aria-selected="true">Task</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{(request()->segment(3)=='comment')?'active':''}}" data-toggle="tab" href="#tasknotes" role="tab" aria-controls="tasknotes" aria-selected="false">Notes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#taskfiles" role="tab" aria-controls="taskfiles" aria-selected="false">Files</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#taskactivity" role="tab" aria-controls="taskactivity" aria-selected="false">Activity</a>
        </li>
        </ul>
        <div class="tab-content">

        <div class="tab-pane fade show {{(empty(request()->segment(3)) | request()->segment(3)=='checklist')?'active':''}}" id="task" role="tabpanel">

            @can('create checklist')
            @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('show checklist',$perArr)))

            <div class="content-list" data-filter-list="checklist">
            <div class="row content-list-head">
                <form method="POST" id="form-checklist" data-remote="true" action="{{ route('tasks.checklist.store',$task->id) }}">
                    <div class="form-group row align-items-center">
                        <div class ="col">
                            <h3>{{__('Checklist')}}</h3>
                        </div>
                        <div class ="col">
                            <button type="submit" class="btn btn-round" data-disable-with="..." data-title={{__('Add')}} >
                                <i class="material-icons">add</i>
                            </button>
                        </div>
                    </div>
                </form>
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
                        <input type="checkbox" class="custom-control-input" name="status" id="checklist-{{$checkList->id}}" data-id="{{$task->id}}" {{($checkList->status==1)?'checked':''}} value="{{$checkList->id}}" data-url="{{route('tasks.checklist.update', [$task->id,$checkList->id])}}" data-remote="true" data-method="put" data-type="text">
                        <label class="custom-control-label" for="checklist-{{$checkList->id}}"></label>
                        <div>
                        <input type="text" name="name" id="name-{{$checkList->id}}" placeholder="{{__('Checklist item')}}" value="{{$checkList->name}}" data-filter-by="value" data-url="{{route('tasks.checklist.update', [$task->id,$checkList->id])}}" data-remote="true" data-method="put" data-type="text"/>
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
        <div class="tab-pane fade show {{(request()->segment(3)=='comment')?'active':''}}" id="tasknotes" role="tabpanel">

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

                <form method="POST" id="form-comment" data-remote="true" action="{{route('tasks.comment.store', $task->id)}}">
                    <div class="form-group row align-items-center">
                        <div class ="col-11">
                            <textarea class="form-control" name="comment" placeholder="{{ __('Write message')}}" id="example-textarea" rows="3" required></textarea>
                        </div>
                        <div class ="col-1">
                            <button type="submit" class="btn btn-round" data-title={{__('Add')}}>
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
                    <img alt="{{$comment->user->name}}" {!! empty($comment->user->avatar) ? "avatar='".$comment->user->name."'" : "" !!} class="avatar" src="{{asset(Storage::url("avatar/".$comment->user->avatar))}}" data-filter-by="alt"/>
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
                            <a class="dropdown-item disabled" href="#">{{__('Edit')}}</a>
                            <a href="{{route('tasks.comment.destroy', [$task->id,$comment->id])}}" class="dropdown-item text-danger" data-method="delete" data-remote="true">
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
        <div class="tab-pane fade show" id="taskfiles" role="tabpanel" data-filter-list="dropzone-previews">
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
                                <img alt="{{$current_user->name}}" {!! empty($current_user->avatar) ? "avatar='".$current_user->name."'" : "" !!} class="avatar" src="{{asset(Storage::url("avatar/".$current_user->avatar))}}" data-filter-by="alt"/>
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
                @include('activity.index')
            </div>
            </div>
            <!--end of content list-->
        </div>
        </div>
    </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
@endsection

<script>

    $(document).on("change", "#checklist input[type=checkbox]", function () {

        var checked = 0;
        var count = 0;
        var percentage = 0;

        count = $("#checklist input[type=checkbox]").length;
        checked = $("#checklist input[type=checkbox]:checked").length;
        percentage = parseInt(((checked / count) * 100), 10);
        if(isNaN(percentage)){
            percentage=0;
        }

        var id = $(this).data("id");
        var selector = '#taskProgress' + id;

        console.log(id);

        $("#taskProgressLabel").text(percentage + "%");
        $(selector).css('width', percentage + '%');


        $(selector).removeClass('bg-warning');
        $(selector).removeClass('bg-primary');
        $(selector).removeClass('bg-success');
        $(selector).removeClass('bg-danger');

        if (percentage <= 15) {
            $(selector).addClass('bg-danger');
        } else if (percentage > 15 && percentage <= 33) {
            $(selector).addClass('bg-warning');
        } else if (percentage > 33 && percentage <= 70) {
            $(selector).addClass('bg-primary');
        } else {
            $(selector).addClass('bg-success');
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
    url: "{{route('tasks.file.upload',[$task->id])}}",

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
