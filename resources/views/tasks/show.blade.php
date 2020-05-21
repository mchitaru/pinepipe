
@extends('layouts.modal')

@php clock()->startEvent('tasks.show', "Show task"); @endphp

@php
use Carbon\Carbon;
use App\Project;
use App\Stage;

$current_user=\Auth::user();

$task_status = $task->stage->name;
$total_task = $task->getTotalChecklistCount();
$completed_task=$task->getCompleteChecklistCount();

$percentage=0;
if($total_task!=0){
    $percentage = intval(($completed_task / $total_task) * 100);
}

$label = $task->getProgressColor($percentage);
$dz_id = 'task-files-dz';

$stage_done = \Auth::user()->getLastTaskStage()->id;

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

@endpush

@section('title')
<b>{{$task->title}}</b>
@endsection

@section('content')

{{-- <div class="modal-body container-fluid"> --}}
 <div class="row justify-content-center" data-remote="true">
    <div class="col">
        <div class="page-header pt-2">
        <p class="lead">{!! nl2br(e($task->description)) !!}</p>
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <ul class="avatars">

                    @foreach($task->users as $user)
                    <li>
                        <a href="{{ route('users.index',$user->id) }}" data-toggle="tooltip" title="{{$user->name}}">
                            {!!Helpers::buildUserAvatar($user)!!}
                        </a>
                    </li>
                    @endforeach

                </ul>
            </div>
            <div class="row">
                @if(!empty($task->project))
                    <i class="material-icons" title="project">folder</i>
                    <a href="{{ route('projects.show',$project->id) }}" data-toggle="tooltip" title={{__('Project')}}>
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
                <div class="d-flex align-items-center" data-toggle="tooltip" title={{__('Priority')}}>
                    {!! Helpers::getPriorityBadge($task->priority) !!}
                </div>
                {{-- <div class="d-flex align-items-center" data-toggle="tooltip" title={{__('Completed')}}>
                    <i class="material-icons">playlist_add_check</i>
                    <span class="badge badge-secondary">{{ $task_status }}</span>
                </div> --}}
                {!!\Helpers::showDateForHumans($task->due_date, __('Due'))!!}
            </div>
        </div>
        </div>

        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{(empty(request()->segment(3)) || request()->segment(3)=='subtask')?'active':''}}" data-toggle="tab" href="#task" role="tab" aria-controls="task" aria-selected="true">{{__('Subtasks')}}
                    <span class="badge badge-secondary">{{ $subtasks->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{(request()->segment(3)=='comment')?'active':''}}" data-toggle="tab" href="#tasknotes" role="tab" aria-controls="tasknotes" aria-selected="false">{{__('Comments')}}
                    <span class="badge badge-secondary">{{ $task->comments->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{(request()->segment(3)=='file')?'active':''}}" data-toggle="tab" href="#taskfiles" role="tab" aria-controls="taskfiles" aria-selected="false">{{__('Files')}}
                    <span class="badge badge-secondary">{{ count($files) }}</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show {{(empty(request()->segment(3)) || request()->segment(3)=='subtask')?'active':''}}" id="task" role="tabpanel">

                @can('view task')
                <div class="content-list">
                    <div class="row content-list-head">
                        <form method="POST" id="form-checklist" data-remote="true" action="{{ route('tasks.subtask.store',$task->id) }}">
                            <div class="form-group row align-items-center">
                                <div class ="col">
                                    <h3>{{__('Subtasks')}}</h3>
                                </div>
                                <div class ="col">
                                    <button id="btn-subtask" type="submit" class="btn btn-round" data-disable="true" data-title={{__('Add')}} >
                                        <i class="material-icons">add</i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

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
                            <a href="#" data-toggle="tooltip" title={{$comment->user->name}}>
                                {!!Helpers::buildUserAvatar($comment->user)!!}
                            </a>

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
    @include('partials.app.timesheetctrl')

    <a href="{{ route('tasks.update', $task->id) }}" class="btn btn-outline-success" data-params="stage_id={{$stage_done}}&archived=1" data-method="PATCH" data-remote="true" data-type="text">
        {{__('Mark as Done')}}
    </a>

    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
@endsection

@php clock()->endEvent('tasks.show'); @endphp
