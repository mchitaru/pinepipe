
@extends('layouts.modal')

@php clock()->startEvent('tasks.show', "Show task"); @endphp

@php
use Carbon\Carbon;
use App\Project;
use App\Stage;

$task_status = $task->stage->name;
$total_task = $task->getTotalChecklistCount();
$completed_task=$task->getCompleteChecklistCount();

$percentage=0;
if($total_task!=0){
    $percentage = intval(($completed_task / $total_task) * 100);
}

$label = 'bg-'.Helpers::getProgressColor($percentage);
$dz_id = 'task-files-dz';
$model = $task;
@endphp

@section('size')
modal-lg
@endsection

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

    $("input[name='title']").keypress(function(e) {

        var keycode = (e.keyCode ? e.keyCode : e.which);

        if(keycode == '13'){
            
            e.preventDefault();

            $(this).blur();

            document.getElementById("btn-subtask").click();
        }
    });

    $("input[name='title']" ).focus(function() {

        var order = $(this).data('order');

        $('#order').val(order);
    });

    // $("#edit-comment").click(function(){
    //     $('.editable').editable('open');
    // });

    $(function() {

        const sortableChecklist = new Draggable.Sortable(document.querySelectorAll('form.checklist, .drop-to-delete'), {
            plugins: [Draggable.Plugins.SwapAnimation],
            draggable: '.checklist > .row',
            handle: '.form-group > span > i',
        });

        sortableChecklist.on('sortable:stop', (evt) => {

            var order = [];

            var list = sortableChecklist.getDraggableElementsForContainer(evt.newContainer);

            for (var i = 0; i < list.length; i++)
            {
                order[i] = list[i].attributes['data-id'].value;
            }

            var check_id = evt.oldContainer.children[evt.oldIndex].attributes['data-id'].value;
            var container_id = evt.newContainer.attributes['data-id'].value;

            $.ajax({
                url: '{{route('tasks.subtask.order', $task->id)}}',
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

        initDropzone('#{{$dz_id}}', '{{route('tasks.file.upload',[$task->id])}}', '{{$task->id}}', {!! json_encode($files) !!});
    });
</script>

@if ($subtask = Session::get('subtask'))
    <script>$(function() { $("input[id='title-{!! $subtask !!}']:text:visible:last").focus(); });</script>
@endif

@endpush

@section('title')
<b>{{ $task->title }}</b>
@endsection

@section('content')

{{-- <div class="modal-body container-fluid"> --}}
 <div class="row justify-content-center" data-remote="true">
    <div class="col">
        <div class="page-header pt-2 text-break">
        <p class="lead">{!! nl2br(Helpers::purify($task->description)) !!}</p>
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <ul class="avatars">

                    @foreach($task->users as $user)
                    <li>
                        <a href="{{route('collaborators')}}"  title="{{$user->name}}">
                            {!!Helpers::buildUserAvatar($user)!!}
                        </a>
                    </li>
                    @endforeach

                </ul>
            </div>
            <div class="row">
                @if(!empty($task->project))
                    <i class="material-icons" title="project">folder</i>
                    <a href="{{ route('projects.show',$project->id) }}"  title={{__('Project')}}>
                        <h5>{{ $task->project->name }}</h5>
                    </a>
                @endif
            </div>
        </div>
        <div>
            <div class="progress mt-0">
                <div class="progress-bar task-progress-{{$task->id}} {{$label}}" style="width:{{$percentage}}%;"></div>
            </div>

            <div class="d-flex justify-content-between text-small">
                <div class="d-flex align-items-center"  title={{__('Priority')}}>
                    {!! Helpers::getPriorityBadge($task->priority) !!}
                </div>
                {{-- <div class="d-flex align-items-center"  title={{__('Completed')}}>
                    <i class="material-icons">playlist_add_check</i>
                    <span class="badge badge-light">{{ $task_status }}</span>
                </div> --}}
                {!!\Helpers::showDateForHumans($task->due_date, __('Due'))!!}
            </div>
        </div>
        </div>
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{(empty(request()->segment(3)) || request()->segment(3)=='subtask')?'active':''}}" data-toggle="tab" href="#task" role="tab" aria-controls="task" aria-selected="true">{{__('Subtasks')}}
                    @if(!$subtasks->isEmpty())
                        <span class="badge badge-light bg-white">{{ $subtasks->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{(request()->segment(3)=='comment')?'active':''}}" data-toggle="tab" href="#taskcomments" role="tab" aria-controls="taskcomments" aria-selected="false">{{__('Comments')}}
                    @if(!$task->comments->isEmpty())
                        <span class="badge badge-light bg-white">{{ $task->comments->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{(request()->segment(3)=='file')?'active':''}}" data-toggle="tab" href="#taskfiles" role="tab" aria-controls="taskfiles" aria-selected="false">{{__('Files')}}
                    @if(!empty($files))
                        <span class="badge badge-light bg-white">{{ count($files) }}</span>
                    @endif
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show {{(empty(request()->segment(3)) || request()->segment(3)=='subtask')?'active':''}}" id="task" role="tabpanel">
                @can('viewAny', 'App\Task')
                <div class="content-list">
                    <form method="POST" id="form-checklist" data-remote="true" action="{{ route('tasks.subtask.store',$task->id) }}">
                        <input type="hidden" id="order" name="order" value="{{$subtasks->isEmpty()?0:$subtasks->last()->order+1}}">
                        <div class="row content-list-head">
                            <div class ="col-auto">
                                <h3>{{__('Subtasks')}}</h3>
                                @can('create', ['App\Checklist', $task])
                                <button id="btn-subtask" type="submit" class="btn btn-primary btn-round" data-disable="true" data-title={{__('Add')}} >
                                    <i class="material-icons">add</i>
                                </button>
                                @endcan
                            </div>
                        </div>
                    </form>
                <!--end of content list head-->
                <div class="content-list-body">
                    <form class="checklist" id="checklist" data-id='sort'>
                        @include('tasks.partials.checklist')
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
                @endcan
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show {{(request()->segment(3)=='comment')?'active':''}}" id="taskcomments" role="tabpanel">
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
                            @can('create', ['App\Comment', $task])
                            <div class ="col-11">
                                <textarea class="form-control" name="comment" placeholder="{{ __('Type your comment...')}}" id="comment" rows="3" required></textarea>
                            </div>
                            <div class ="col-1">
                                <button type="submit" class="btn btn-primary btn-round" data-disable="true" data-title={{__('Add')}}>
                                    <i class="material-icons">add</i>
                                </button>
                            </div>
                            @endcan
                        </div>
                    </form>
                    <div id="comments">
                    @foreach($task->comments as $comment)
                        @can('view', $comment)
                        <div class="card card-note">
                            <div class="card-header p-1">
                                @if($comment->user)
                                <div class="media align-items-center">
                                    <a href="#" title={{$comment->user->name}}>
                                        {!!Helpers::buildUserAvatar($comment->user)!!}
                                    </a>
                                    <div class="media-body">
                                        <h6 class="mb-0" data-filter-by="text">{{$comment->user?$comment->user->name:__('Unknown')}}</h6>
                                    </div>
                                </div>
                                @endif
                                <div class="d-flex align-items-center">
                                <span data-filter-by="text">{{$comment->created_at->diffForHumans()}}</span>
                                @can('delete', $comment)
                                    <div class="ml-1 dropdown card-options">
                                        <button class="btn-options" type="button" id="note-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            {{-- <a id="edit-comment" class="dropdown-item" href="#">{{__('Edit')}}</a> --}}
                                            @can('delete', $comment)
                                            <a href="{{route('tasks.comment.destroy', [$task->id,$comment->id])}}" class="dropdown-item text-danger" data-method="delete" data-remote="true">
                                                {{__('Delete')}}
                                            </a>
                                            @endcan
                                        </div>
                                    </div>
                                @endcan
                                </div>
                            </div>
                            <div class="card-body p-1 editable text-break" id="comment" data-filter-by="text">
                                    {!! nl2br(Helpers::purify($comment->comment)) !!}
                            </div>
                        </div>
                        @endcan
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
                            <h3>{{__('Files')}}</h3>
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
    @can('update', [$task, true])
        @include('partials.app.timesheetctrl')
        <a href="{{ route('tasks.update', $task->id) }}" class="btn btn-outline-success" data-params="closed=1&archived=1" data-method="PATCH" data-remote="true" data-type="text">
            {{__('Mark as Done')}}
        </a>
    @endcan
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
@endsection

@php clock()->endEvent('tasks.show'); @endphp
