@php clock()->startEvent('tasks.board', "Display tasks"); @endphp

@php
use Carbon\Carbon;
use App\Project;
@endphp

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
                        <a class="dropdown-item disabled" href="#">{{__('Edit')}}</a>
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
            <div class="progress-bar task-progress-{{$task->id}} {{$label}}" role="progressbar" style="width: {{$task_percentage}}%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <div class="card-body">
                <div class="dropdown card-options">
                <button class="btn-options" type="button" id="kanban-dropdown-button-14" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    @can('edit task')
                        <a href="{{ route('tasks.edit',$task->id) }}" class="dropdown-item" data-remote="true" data-type="text">
                            {{__('Edit')}}
                        </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger disabled" href="#">{{__('Archive')}}</a>
                    @can('delete task')
                        <a href="{{route('tasks.destroy',$task->id)}}" class="dropdown-item text-danger" data-method="delete" data-remote="true" data-type="text">
                            {{__('Delete')}}
                        </a>
                    @endcan
                </div>
                </div>
                <div class="card-title">
                    <a href="{{route('tasks.show', $task->id)}}#task" class="text-body" data-remote="true" data-type="text">
                        <h6 data-filter-by="text" class="text-truncate">{{$task->title}}</h6>
                    </a>        

                </div>

                @if($task->priority =='low')
                    <span class="badge badge-success" data-toggle="tooltip" title="{{__('Priority')}}"> {{ $task->priority }}</span>
                @elseif($task->priority =='medium')
                    <span class="badge badge-warning" data-toggle="tooltip" title="{{__('Priority')}}"> {{ $task->priority }}</span>
                @elseif($task->priority =='high')
                    <span class="badge badge-danger" data-toggle="tooltip" title="{{__('Priority')}}"> {{ $task->priority }}</span>
                @endif

                <div class="d-flex justify-content-between">
                    <ul class="avatars">
                        @foreach($task->users as $user)
                        <li>
                            <a href="{{ route('users.index',$user->id) }}" data-toggle="tooltip" title="{{$user->name}}">
                                {!!Helpers::buildUserAvatar($user)!!}
                            </a>
                        </li>
                        @endforeach
                    </ul>

                    <div data-toggle="tooltip" title="{{__('Labels')}}">
                        @foreach($task->tags as $tag)
                            <span class="badge badge-secondary" data-filter-by="text"> {{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
                
                <div class="card-meta d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="material-icons">playlist_add_check</i>
                        <p class="small @if($total_subtask==0) text-muted @endif @if($completed_subtask==$total_subtask && $completed_subtask!=0) text-success @else text-danger @endif">
                            <span>{{$completed_subtask}}/{{$total_subtask}}</span>
                        </p>
                    </div>

                    <span class="text-small {{($task->due_date && $task->due_date<now())?'text-danger':''}}">
                        {{$task->due_date?__('Due ').Carbon::parse($task->due_date)->diffForHumans(): '---' }}
                    </span>

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
    <button class="btn btn-link btn-sm text-small">{{__('Add stage')}}</button>
    </div>
</div>

@php clock()->endEvent('tasks.board'); @endphp
