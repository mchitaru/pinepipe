@php
use App\Project;
use Carbon\Carbon;

$stage_done = \Auth::user()->getLastTaskStage()->id;
@endphp

<div class="scrollable-list col-lg-4 col-md-12" style="max-height:50vh">
    <div class="card-list">
        <div class="card-list-head">
        <h6>{{__($title)}} ({{count($tasks) + count($events)}})</h6>
        <button class="btn-options" type="button" data-toggle="collapse" data-target="#{{$title}}">
            <i class="material-icons">more_horiz</i>
        </button>
        </div>
        <div class="card-list-body collapse show" id="{{$title}}">
            @if(count($tasks)+count($events) == 0)
                <div class="card-empty-text">
                    {{__('Hooray! Nothing here.')}}
                </div>
            @endif
            {{-- tasks --}}
            @foreach($tasks as $key => $task)
            @php
                $due = Carbon::parse($task->due_date);
            @endphp
            <div class="card card-task">
                <div class="card-body p-2">
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
                                @can('edit task')
                                    <a href="{{ route('tasks.update', $task->id) }}" class="dropdown-item" data-params="stage_id={{$stage_done}}" data-method="PATCH" data-remote="true" data-type="text">
                                        {{__('Mark as done')}}
                                    </a>

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
                    </div>
                </div>
            </div>
            @endforeach
            {{-- events --}}
            @foreach($events as $key => $event)
            <div class="card card-task">
                <div class="card-body p-2">
                    <div class="card-title">
                        <a href="{{ route('events.edit', $event->id) }}" data-remote="true" data-type="text">
                            <h6 data-filter-by="text">{{$event->name}}</h6>
                        </a>
                        {!!\Helpers::showTimeForHumans($event->start)!!}
                        {!!\Helpers::showTimespan($event->start, $event->end)!!}
                    </div>
                    <div class="card-meta float-right">
                        <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @can('edit event')
                                <a class="dropdown-item" href="{{ route('events.edit', $event->id) }}" data-remote="true" data-type="text">
                                    <span>{{__('Edit')}}</span>
                                </a>
                                @endcan
                                <div class="dropdown-divider"></div>
                                @can('delete event')
                                    <a class="dropdown-item text-danger" href="{{ route('events.destroy', $event->id) }}" data-method="delete" data-remote="true" data-type="text">
                                        <span>{{'Delete'}}</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
