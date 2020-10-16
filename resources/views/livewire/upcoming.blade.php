@php
use App\Project;
use Carbon\Carbon;

$count = count($tasks) + count($events);
@endphp

<div class="scrollable-list col-lg-4 col-md-12" style="max-height:50vh">
    <div class="card-list">
        <div class="card-list-head">
        <h5>{{__($title)}} <span class="badge badge-{{$count ? 'warning':'light bg-white'}}">{{$count}}</span></h5>
        {{-- <button class="btn-options" type="button" data-toggle="collapse" data-target="#{{$title}}">
            <i class="material-icons">more_horiz</i>
        </button> --}}
        </div>
        <div class="card-list-body collapse show" id="{{$title}}">
            @if(count($tasks)+count($events) == 0)
                <div class="card-empty-text">
                    {{__('Hooray! Nothing here.')}}
                </div>
            @endif
            {{-- tasks --}}
            @foreach($tasks as $key => $task)
            @can('view', $task)
            @php
                $due = Carbon::parse($task->due_date);

                $total_subtask = $task->getTotalChecklistCount();
                $completed_subtask = $task->getCompleteChecklistCount();

                $task_percentage=0;
                if($total_subtask!=0){
                    $task_percentage = intval(($completed_subtask / $total_subtask) * 100);
                }

                $label = 'bg-'.Helpers::getProgressColor($task_percentage);
            @endphp
            <div class="card card-task">
                <div class="progress">
                    <div class="progress-bar task-progress-{{$task->id}} {{$label}}" role="progressbar" style="width: {{$task_percentage}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="card-body">
                    <div class="card-title">
                        <a href="{{ route('tasks.show', $task->id) }}" data-remote="true" data-type="text">
                            <h6 data-filter-by="text">{{$task->title}}</h6>
                        </a>
                        {!!\Helpers::showDateForHumans($task->due_date)!!}
                    </div>
                    <div class="card-meta float-right">
                        <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @can('update', [$task, true])
                                    <a href="{{ route('tasks.update', $task->id) }}" class="dropdown-item" data-params="closed=1" data-method="PATCH" data-remote="true" data-type="text">
                                        {{__('Mark as done')}}
                                    </a>
                                @endcan
                                @can('update', $task)
                                    <a href="{{ route('tasks.edit',$task->id) }}" class="dropdown-item" data-remote="true" data-type="text">
                                        {{__('Edit')}}
                                    </a>
                                @endcan
                                @can('delete', $task)
                                    <div class="dropdown-divider"></div>
                                    <a href="{{route('tasks.destroy',$task->id)}}" class="dropdown-item text-danger" data-method="delete" data-remote="true" data-type="text">
                                        {{__('Delete')}}
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            @endforeach
            {{-- events --}}
            @foreach($events as $key => $event)
            @can('view', $event)
            <div class="card card-task">
                <div class="card-body">
                    <div class="card-title">
                        @if(Gate::check('update', $event))
                        <a href="{{ route('events.edit', $event->id) }}" data-remote="true" data-type="text">
                            <h6 data-filter-by="text">{{$event->name}}</h6>
                        </a>
                        @else
                        <a href="{{ route('events.show', $event->id) }}" data-remote="true" data-type="text">
                            <h6 data-filter-by="text">{{$event->name}}</h6>
                        </a>
                        @endif
                        {!!\Helpers::showTimeForHumans($event->start)!!}
                        {!!\Helpers::showTimespan($event->start, $event->end)!!}
                    </div>
                    <div class="card-meta float-right">
                        <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @can('update', $event)
                                <a class="dropdown-item" href="{{ route('events.edit', $event->id) }}" data-remote="true" data-type="text">
                                    <span>{{__('Edit')}}</span>
                                </a>
                                @endcan
                                <div class="dropdown-divider"></div>
                                @can('delete', $event)
                                    <a class="dropdown-item text-danger" href="{{ route('events.destroy', $event->id) }}" data-method="delete" data-remote="true" data-type="text">
                                        <span>{{__('Delete')}}</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
            @endforeach
        </div>
    </div>
</div>
