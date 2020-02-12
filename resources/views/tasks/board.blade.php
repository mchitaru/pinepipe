@extends('layouts.app')

@php clock()->startEvent('tasks.board', "Display tasks"); @endphp

@php
use Carbon\Carbon;
use App\Project;
use App\Http\Helpers;
@endphp

@push('stylesheets')
@endpush

@push('scripts')

<script type="text/javascript" src="{{ asset('assets/js/draggable.bundle.min.js') }}"></script>

<script>

    const sortableLists = new Draggable.Sortable(document.querySelectorAll('div.kanban-board'), {
        draggable: '.kanban-col:not(:last-child)',
        handle: '.card-list-header',
    });

    const sortableCards = new Draggable.Sortable(document.querySelectorAll('.kanban-col .card-list-body'), {
        plugins: [Draggable.Plugins.SwapAnimation],
        draggable: '.card-kanban',
        handle: '.card-kanban',
        appendTo: 'body',
    });

    sortableCards.on('sortable:stop', (evt) => {

        var order = [];
        
        var list = sortableCards.getDraggableElementsForContainer(evt.newContainer);

        for (var i = 0; i < list.length; i++) 
        {
            order[i] = list[i].attributes['data-id'].value;
        }
        
        var task_id = evt.newContainer.children[evt.newIndex].attributes['data-id'].value;
        var stage_id = evt.newContainer.attributes['data-id'].value;

        $(evt.oldContainer).prev().find('.count').text('(' + sortableCards.getDraggableElementsForContainer(evt.oldContainer).length + ')');
        $(evt.newContainer).prev().find('.count').text('(' + sortableCards.getDraggableElementsForContainer(evt.newContainer).length + ')');

        $.ajax({
            url: '{{route('tasks.order')}}',
            type: 'POST',
            data: {task_id: task_id, stage_id: stage_id, order: order, "_token": $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                // console.log('success');
            },
            error: function (data) {
                // console.log('error');
            }
        });
    });

</script>

@endpush

@section('page-title')
    {{__('Task Board')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            @if($project)
                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('projects.show',$project->id) }}">{{$project->name}}</a>
                </li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{__('Tasks')}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item disabled" href="#">{{__('New Task')}}</a>

        </div>
    </div>
</div>
@endsection

@section('content')
    <div class="container-kanban" data-filter-list="card-list-body">
        <div class="container-fluid page-header d-flex justify-content-between align-items-start">
            <div class="col">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Tasks')}}</h3>

                        <a href="{{ route('projects.task.create', '0') }}" class="btn btn-round" data-remote="true" data-type="text">
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
            </div>
        </div>
        {{-- <div class="content-list-body">

            @include('tasks.index')
        <!--end of content list body-->
        </div> --}}

        <div class="kanban-board container-fluid mt-lg-3">

            @foreach($stages as $stage)
            
            <div class="kanban-col">
                <div class="card-list">
                <div class="card-list-header">
                    <div class="col">
                        <div class="row">
                            <h6>{{$stage->name}}</h6>
                            <span class="small count">({{ $stage->tasks->count() }})</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Edit</a>
                                <a class="dropdown-item text-danger" href="#">Archive List</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-list-body" data-id={{$stage->id}}>

                    {{-- <div class="card card-kanban">

                    <div class="card-body">
                        <div class="dropdown card-options">
                        <button class="btn-options" type="button" id="kanban-dropdown-button-13" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Edit</a>
                            <a class="dropdown-item text-danger" href="#">Archive Card</a>
                        </div>
                        </div>
                        <div class="card-title">
                        <a href="#" data-toggle="modal" data-target="#task-modal">
                            <h6>A/B testing</h6>
                        </a>
                        </div>

                    </div>
                    </div> --}}

                    @foreach($stage->tasks as $task)
    
                    @php
                        $total_subtask = $task->getTotalChecklistCount();
                        $completed_subtask = $task->getCompleteChecklistCount();
            
                        $task_percentage=0;
                        if($total_subtask!=0){
                            $task_percentage = intval(($completed_subtask / $total_subtask) * 100);
                        }
            
                        $label = Project::getProgressColor($task_percentage);
                        
                    @endphp
            
                    <div class="card card-kanban" data-id={{$task->id}}>

                    <div class="progress">
                      <div class="progress-bar {{$label}}" id="taskProgress{{$task->id}}" role="progressbar" style="width: {{$task_percentage}}%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="card-body">
                        <div class="dropdown card-options">
                        <button class="btn-options" type="button" id="kanban-dropdown-button-14" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Edit</a>
                            <a class="dropdown-item text-danger" href="#">Archive Card</a>
                        </div>
                        </div>
                        <div class="card-title">
                            <a href="{{route('tasks.show', $task->id)}}#task" class="text-body" data-remote="true" data-type="text">
                                <h6 data-filter-by="text" class="text-truncate">{{$task->title}}</h6>
                            </a>        
                        </div>

                        @if($task->priority =='low')
                            <span class="badge badge-success"> {{ $task->priority }}</span>
                        @elseif($task->priority =='medium')
                            <span class="badge badge-warning"> {{ $task->priority }}</span>
                        @elseif($task->priority =='high')
                            <span class="badge badge-danger"> {{ $task->priority }}</span>
                        @endif

                        <ul class="avatars">

                            @foreach($task->users as $user)
                            <li>
                                <a href="{{ route('users.index',$user->id) }}" data-toggle="tooltip" title="{{$user->name}}">
                                    {!!Helpers::buildAvatar($user)!!}
                                </a>
                            </li>
                            @endforeach
                        </ul>
    
                        <div class="card-meta d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="material-icons">playlist_add_check</i>
                            <p class="small @if($total_subtask==0) text-muted @endif @if($completed_subtask==$total_subtask && $completed_subtask!=0) text-success @else text-danger @endif">
                                <span>{{$completed_subtask}}/{{$total_subtask}}</span>
                            </p>
                        </div>

                        <span class="text-small">{{__('Due')}} {{ Carbon::parse($task->due_date)->diffForHumans() }}</span>

                        </div>

                    </div>
                    </div>

                    @endforeach

                </div>
                </div>
            </div>

        @endforeach

        <div class="kanban-col">
            <div class="card-list">
            <button class="btn btn-link btn-sm text-small">Add list</button>
            </div>
        </div>
        </div>
    </div>
@endsection

@php clock()->endEvent('tasks.board'); @endphp
