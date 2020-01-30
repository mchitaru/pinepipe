@extends('layouts.app')

@php
use Carbon\Carbon;
use App\Project;
@endphp

@push('stylesheets')
@endpush

@push('scripts')
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
            <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">{{__('Tasks')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Task Board')}}</li>
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
    <div class="container-kanban">
        <div class="container-fluid page-header d-flex justify-content-between align-items-start">
            <div class="row align-items-center">
                <h3>Task Board</h3>
                <span class="badge badge-secondary">demo</span>
            </div>
        </div>
        <div class="kanban-board container-fluid mt-lg-3">

            @foreach($stages as $stage)
            
            @php $tasks = $stage->getTasksByUserType($project_id)    @endphp

            <div class="kanban-col">
                <div class="card-list">
                <div class="card-list-header">
                    <h6>{{$stage->name}} ({{ count($tasks) }})</h6>
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
                <div class="card-list-body">

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

                    @foreach($tasks as $task)
    
                    @php
                        $total_subtask = $task->getTotalChecklistCount();
                        $completed_subtask = $task->getCompleteChecklistCount();
            
                        $task_percentage=0;
                        if($total_subtask!=0){
                            $task_percentage = intval(($completed_subtask / $total_subtask) * 100);
                        }
            
                        $label = Project::getProgressColor($task_percentage);
                        
                    @endphp
            
                    <div class="card card-kanban">

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
                            <a href="{{route('tasks.show',$task->id)}}#task" class="text-body" data-remote="true" data-type="text">
                                <h6 data-filter-by="text" class="text-truncate" style="max-width: 150px;">{{$task->title}}</h6>
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

                        <li>
                            @if(!empty($task->task_user))
                            <a href="#" data-toggle="tooltip" title="" data-original-title="{{(!empty($task->task_user)?$task->task_user->name:'')}}">
                                <img alt="{{$task->task_user->name}}" {!! empty($task->task_user->avatar) ? "avatar='".$task->task_user->name."'" : "" !!} class="avatar" src="{{Storage::url($task->task_user->avatar)}}" data-filter-by="alt"/>
                            </a>
                            @endif
                        </li>

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
