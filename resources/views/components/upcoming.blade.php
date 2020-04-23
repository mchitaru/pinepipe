@php
use App\Project;
use Carbon\Carbon;
@endphp

<div class="scrollable-list col-lg-4 col-xs-12 col-sm-12" style="max-height:50vh">
    <div class="card-list">
        <div class="card-list-head">
        <h6>{{$title}} ({{count($tasks) + count($events)}})</h6>
        <button class="btn-options" type="button" data-toggle="collapse" data-target="#{{$title}}">
            <i class="material-icons">more_horiz</i>
        </button>
        </div>
        <div class="card-list-body collapse show" style="min-height:186px" id="{{$title}}">
            @if(count($tasks)+count($events) == 0)
                <div class="d-flex align-items-center p-5">
                    Hooray! Nothing here.
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
                                <a class="dropdown-item" href="#">Mark as done</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Archive</a>
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
                    </div>
                    <div class="card-meta float-right">
                        <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Mark as done</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Archive</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
