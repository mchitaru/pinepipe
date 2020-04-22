@php clock()->startEvent('tasks.index', "Display tasks"); @endphp

@php
    use Carbon\Carbon;
    use App\Project;
    use App\TaskStage;

    $current_user=\Auth::user();
    $stage_done = TaskStage::where('created_by', '=', \Auth::user()->creatorId())->get()->last()->id;
@endphp

@foreach($stages as $key=>$stage)

    <div class="card-list">
    <div class="card-list-head ">
        <h6>{{$stage->name}} ({{ $stage->tasks->count() }})</h6>
        <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">more_vert</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item disabled" href="#">{{__('Rename')}}</a>
            <a class="dropdown-item text-danger disabled" href="#">{{__('Archive')}}</a>
            </div>
        </div>
        </div>
        <div class="card-list-body">

        @foreach($stage->tasks as $key=>$task)

        @php
            $total_subtask = $task->getTotalChecklistCount();
            $completed_subtask = $task->getCompleteChecklistCount();

            $task_percentage=0;
            if($total_subtask!=0){
                $task_percentage = intval(($completed_subtask / $total_subtask) * 100);
            }

            $label = Project::getProgressColor($task_percentage);

        @endphp

            <div class="card card-task">
                <div class="progress">
                    <div class="progress-bar task-progress-{{$task->id}} {{$label}}" role="progressbar" style="width: {{$task_percentage}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="card-body">
                <div class="card-title col-xs-12 col-sm-4">

                    <a href="{{route('tasks.show',$task->id)}}#task" class="text-body" data-remote="true" data-type="text">
                        <h6 data-filter-by="text">{{$task->title}}</h6>
                    </a>

                    {!! Helpers::getPriorityBadge($task->priority) !!}

                    <p>
                        <span class="text-small {{($task->due_date && $task->due_date<now())?'text-danger':''}}">
                            {{$task->due_date?__('Due ').Carbon::parse($task->due_date)->diffForHumans(): '---' }}
                        </span>
                    </p>
                </div>
                <div class="card-title d-none d-xl-block col-xs-12 col-sm-4">
                    <div class="row align-items-center" data-toggle="tooltip" title="{{__('Project')}}">
                        <i class="material-icons">folder</i>
                        <span data-filter-by="text" class="text-small">{{ !empty($task->project) ? $task->project->name : '---' }}</span>
                    </div>
                    <div class="row align-items-center" data-toggle="tooltip" title="{{__('Client')}}">
                        <i class="material-icons">apartment</i>
                        <span data-filter-by="text" class="text-small">{{ !empty($task->project) ? $task->project->client->name : '---' }}</span>
                    </div>
                    @if(!$task->tags->isEmpty())
                    <div class="row align-items-center" data-toggle="tooltip" title="{{__('Labels')}}">
                        <i class="material-icons">label</i>
                        @foreach($task->tags as $tag)
                            <span class="badge badge-secondary" data-filter-by="text"> {{ $tag->name }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="card-title col-xs-12 col-sm-3 text-right">

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
                <div class="card-meta float-right">

                    @if(Gate::check('edit task') || Gate::check('delete task'))
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
                    @endif
                </div>
            </div>
        </div>

        @endforeach

        </div>
    </div>
@endforeach

@php clock()->endEvent('tasks.index'); @endphp
