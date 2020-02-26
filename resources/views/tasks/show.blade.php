
@extends('layouts.modal')

@php clock()->startEvent('tasks.show', "Show task"); @endphp

@php
use Carbon\Carbon;
use App\Project;

$current_user=\Auth::user();

$total_task = $task->getTotalChecklistCount();
$completed_task=$task->getCompleteChecklistCount();

$percentage=0;
if($total_task!=0){
    $percentage = intval(($completed_task / $total_task) * 100);
}

$label = $task->getProgressColor($percentage);
$dz_id = 'task-files-dz';
@endphp

@push('scripts')

<script type="text/javascript" src="{{ asset('assets/js/draggable.bundle.min.js') }}"></script>

<script>
    function updateCheck(task_id)
    {
        var checked = 0;
        var count = 0;
        var percentage = 0;
             
        count = $("#checklist input[type=checkbox]").length;
        checked = $("#checklist input[type=checkbox]:checked").length;
        percentage = parseInt(((checked / count) * 100), 10);
        if(isNaN(percentage)){
            percentage=0;
        }

        var id = task_id;
        var selector = '.task-progress-' + id;

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
    }

    $(document).on("change", "#checklist input[type=checkbox]", function () {
        
        updateCheck($(this).data("id"));

    });

    $(function() {

        const sortableChecklist = new Draggable.Sortable(document.querySelectorAll('form.checklist, .drop-to-delete'), {
            plugins: [Draggable.Plugins.SwapAnimation],
            draggable: '.checklist > .row',
            handle: '.form-group > span > i',
        });

        sortableChecklist.on('sortable:stop', (evt) => {

            console.log(evt);
            var order = [];
            
            var list = sortableChecklist.getDraggableElementsForContainer(evt.newContainer);

            for (var i = 0; i < list.length; i++) 
            {
                order[i] = list[i].attributes['data-id'].value;
            }
            
            var check_id = evt.oldContainer.children[evt.oldIndex].attributes['data-id'].value;
            var container_id = evt.newContainer.attributes['data-id'].value;

            $.ajax({
                url: '{{route('tasks.checklist.order', $task->id)}}',
                type: 'POST',
                data: {check_id: check_id, container_id: container_id, order: order, "_token": $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    if(container_id == 'delete')
                    {
                        updateCheck({{$task->id}});
                    }
                    /* console.log('success'); */
                },
                error: function (data) {
                    /* console.log('error'); */
                }
            });
        });    
    });

    $(function() {

        dzTask = $('#{{$dz_id}}').dropzone({
            previewTemplate: document.querySelector('.dz-template').innerHTML,
            createImageThumbnails: false,
            previewsContainer: "#{{$dz_id}}-previews",
            maxFiles: 20,
            maxFilesize: 2,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.svg,.pdf,.txt,.doc,.docx,.zip,.rar",
            url: "{{route('tasks.file.upload',[$task->id])}}",

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
                formData.append("task_id", {{$task->id}});
            },
        })[0];
        
        @php
            $files = $task->files;
        @endphp

        @foreach($files as $file)
            var mockFile = {name: "{{$file->file_name}}", size: {{filesize(storage_path('app/'.$file->file_path))}} };
            dzTask.dropzone.emit("addedfile", mockFile);
            dzTask.dropzone.emit("processing", mockFile);
            dzTask.dropzone.emit("complete", mockFile);

            dropzoneBtn(mockFile, {download: "{{route('tasks.file.download',[$task->id,$file->id])}}", delete: "{{route('tasks.file.delete',[$task->id,$file->id])}}"});
        @endforeach

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

</script>

@endpush

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
                <a href="#" data-toggle="tooltip" title="{{(!empty($task->task_user)?$task->task_user->name:'')}}">
                    {!!Helpers::buildUserAvatar($task->task_user)!!}
                </a>
                @endif
            </li>

            </ul>
        </div>
        <div>
            <div class="d-flex flex-row-reverse">
                <small class="card-text" style="float:right;" >{{$percentage}}%</small>
            </div>
            <div class="progress mt-0">
                <div class="progress-bar task-progress-{{$task->id}} {{$label}}" style="width:{{$percentage}}%;"></div>
            </div>

            <div class="d-flex justify-content-between text-small">
            <div class="d-flex align-items-center">
                <i class="material-icons">playlist_add_check</i>
                <span>{{$completed_task}}/{{$total_task}}</span>
            </div>
            <span class="{{($task->due_date && $task->due_date<now())?'text-danger':''}}">
                {{__('Due') }} {{ Carbon::parse($task->due_date)->diffForHumans() }}
            </span>
            </div>
        </div>
        </div>
        <ul class="nav nav-tabs nav-fill" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{(empty(request()->segment(3)) || request()->segment(3)=='checklist')?'active':''}}" data-toggle="tab" href="#task" role="tab" aria-controls="task" aria-selected="true">{{__('Subtasks')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{(request()->segment(3)=='comment')?'active':''}}" data-toggle="tab" href="#tasknotes" role="tab" aria-controls="tasknotes" aria-selected="false">{{__('Comments')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{(request()->segment(3)=='file')?'active':''}}" data-toggle="tab" href="#taskfiles" role="tab" aria-controls="taskfiles" aria-selected="false">{{__('Files')}}</a>
        </li>
        </ul>
        <div class="tab-content">

        <div class="tab-pane fade show {{(empty(request()->segment(3)) | request()->segment(3)=='checklist')?'active':''}}" id="task" role="tabpanel">

            @can('create checklist')
            <div class="content-list">
            <div class="row content-list-head">
                <form method="POST" id="form-checklist" data-remote="true" action="{{ route('tasks.checklist.store',$task->id) }}">
                    <div class="form-group row align-items-center">
                        <div class ="col">
                            <h3>{{__('Subtasks')}}</h3>
                        </div>
                        <div class ="col">
                            <button type="submit" class="btn btn-round" data-disable="true" data-title={{__('Add')}} >
                                <i class="material-icons">add</i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @endcan

            <!--end of content list head-->
            <div class="content-list-body">
                <form class="checklist" id="checklist" data-id='sort'>

                @foreach($checklist as $key=>$checkList)

                @can('create checklist')
                <div class="row" data-id = "{{$checkList->id}}" tabindex="{{$key}}">
                    <div class="form-group col">
                    <span class="checklist-reorder">
                        <i class="material-icons">reorder</i>
                    </span>
                    <div class="custom-control custom-checkbox col">
                        <input type="checkbox" class="custom-control-input" name="status" id="checklist-{{$checkList->id}}" data-id="{{$task->id}}" {{($checkList->status==1)?'checked':''}} value="{{$checkList->id}}" data-url="{{route('tasks.checklist.update', [$task->id,$checkList->id])}}" data-remote="true" data-method="put" data-type="text" data-disable="true">
                        <label class="custom-control-label" for="checklist-{{$checkList->id}}"></label>
                        <div class="col">
                            <input autofocus class="col" type="text" name="name" id="name-{{$checkList->id}}" placeholder="{{__('Checklist item')}}" value="{{$checkList->name}}" data-filter-by="value" data-url="{{route('tasks.checklist.update', [$task->id,$checkList->id])}}" data-remote="true" data-method="put" data-type="text" data-disable="true"/>
                            <div class="checklist-strikethrough"></div>
                        </div>
                    </div>
                    </div>
                    <!--end of form group-->
                </div>
                @endcan

                @endforeach
                </form>
                <div class="drop-to-delete" data-id='delete'>
                    <div class="drag-to-delete-title">
                        <i class="material-icons">delete</i>
                    </div>
                </div>
            </div>
            <!--end of content list body-->
            </div>
            <!--end of content list-->
        </div>
        <!--end of tab-->
        <div class="tab-pane fade show {{(request()->segment(3)=='comment')?'active':''}}" id="tasknotes" role="tabpanel">

            <div class="content-list">
            <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Comments')}}</h3>
                </div>
            </div>
            <!--end of content list head-->
            <div class="content-list-body">

                <form method="POST" id="form-comment" data-remote="true" action="{{route('tasks.comment.store', $task->id)}}">
                    <div class="form-group row align-items-center">
                        <div class ="col-11">
                            <textarea class="form-control" name="comment" placeholder="{{ __('Type your comment...')}}" id="example-textarea" rows="3" required></textarea>
                        </div>
                        <div class ="col-1">
                            <button type="submit" class="btn btn-round" data-disable="true" data-title={{__('Add')}}>
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
                        {!!Helpers::buildUserAvatar($comment->user)!!}
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
        <div class="tab-pane fade show {{(request()->segment(3)=='file')?'active':''}}" id="taskfiles" role="tabpanel" data-filter-list="dropzone-previews">
            <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>Files</h3>
                    </div>
                </div>
                <!--end of content list head-->
                <div class="content-list-body row">@include('files.index')</div>
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

@php clock()->endEvent('tasks.show'); @endphp
