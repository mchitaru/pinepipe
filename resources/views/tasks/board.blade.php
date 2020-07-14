@php clock()->startEvent('tasks.board', "Display tasks"); @endphp

@php
use Carbon\Carbon;
use App\Project;
$timesheet = $_user->timesheets->first();
@endphp

@foreach($stages as $stage)
<div class="kanban-col" data-id={{$stage->id}}>
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
                        @can('edit task stage')
                            <a class="dropdown-item" href="{{ route('stages.edit',$stage->id) }}" data-remote="true" data-type="text">
                                <span>{{__('Edit')}}</span>
                            </a>
                        @endcan
                        <div class="dropdown-divider"></div>
                        @can('delete task stage')
                            <a class="dropdown-item text-danger" href="{{ route('stages.destroy',$stage->id) }}" data-method="delete" data-remote="true" data-type="text">
                                <span>{{__('Delete')}}</span>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-list-body" data-id={{$stage->id}}>
            @foreach($stage->tasks as $task)
            @php
                $total_subtask = $task->getTotalChecklistCount();
                $completed_subtask = $task->getCompleteChecklistCount();
                $task_percentage=0;
                if($total_subtask!=0){
                    $task_percentage = intval(($completed_subtask / $total_subtask) * 100);
                }
                $label = 'bg-'.Helpers::getProgressColor($task_percentage);
            @endphp
            <div class="card card-kanban task {{($timesheet&&($timesheet->task_id==$task->id)&&$timesheet->isStarted())?'glow-animation':''}}" data-id={{$task->id}}>
                <div class="progress">
                    <div class="progress-bar task-progress-{{$task->id}} {{$label}}" role="progressbar" style="width: {{$task_percentage}}%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="card-body p-2">
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
                        @if($task->project)
                            @if(Gate::check('view project'))
                            <a class title='{{__('Project')}}' href="{{ route('projects.show',$task->project->id) }}">
                                <p><span data-filter-by="text" class="text-small">{{ $task->project->name }}</span></p>
                            </a>
                            @else
                                <p><span data-filter-by="text" class="text-small">{{ $task->project->name }}</span></p>
                            @endif
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        {!! Helpers::getPriorityBadge($task->priority) !!}
                        <ul class="avatars">
                            @foreach($task->users as $user)
                            <li>
                                <a href="{{ route('users.index',$user->id) }}"  title="{{$user->name}}">
                                    {!!Helpers::buildUserAvatar($user)!!}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div  title="{{__('Labels')}}">
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
                        {!!\Helpers::showDateForHumans($task->due_date, __('Due'))!!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach

@can('create task stage')
<div class="kanban-col">
    <div class="card-list">
        <a href="{{ route('stages.create') }}" class="btn btn-link btn-sm text-small" data-params="class=App\Task&order={{$stages->last()?($stages->last()->order + 1):0}}" data-remote="true" data-type="text">
            {{__('Add Stage')}}
        </a>
    </div>
</div>
@endcan

@php clock()->endEvent('tasks.board'); @endphp
