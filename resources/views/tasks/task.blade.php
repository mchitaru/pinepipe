@php
use Carbon\Carbon;
use App\Project;

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
    <div class="card-title col-xs-12 col-sm-6 align-items-center">
        <a href="{{route('tasks.show',$task->id)}}#task" class="text-body" data-remote="true" data-type="text" title="{{$task->title}}">
            <h6 data-filter-by="text" class="text-truncate">{{$task->title}}</h6>
        </a>
        <div>{!! Helpers::getPriorityBadge($task->priority) !!}</div>            
    </div>
    <div class="card-title d-none d-xl-block col-xs-12 col-sm-2">
        <div class="row align-items-center"  title="{{__('Client')}}">
            {!!\Helpers::showDateForHumans($task->due_date)!!}
            {{-- <i class="material-icons">business</i>
            @if($task->project)
                <a href="{{route('clients.show',$task->project->client->id)}}" title="{{__('Client')}}">
                    <span data-filter-by="text" class="text-small">{{$task->project->client->name}}</span>
                </a>
            @else
                <span data-filter-by="text" class="text-small">---</span>
            @endif --}}
        </div>
        <div class="row align-items-center"  title="{{__('Project')}}">
            @if(!$task->tags->isEmpty())
                @foreach($task->tags as $tag)
                    <span class="badge badge-light" data-filter-by="text"> {{ $tag->name }}</span>
                @endforeach
            @endif
            {{-- <i class="material-icons">folder</i>
            @if($task->project)
                <a href="{{route('projects.show',$task->project->id)}}" title="{{__('Project')}}">
                    <span data-filter-by="text" class="text-small">{{$task->project->name}}</span>
                </a>
            @else
                <span data-filter-by="text" class="text-small">---</span>
            @endif --}}
        </div>
    </div>
    <div class="card-title col-xs-12 col-sm-3 text-right">
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
    <div class="card-meta float-right">
        @if(Gate::check('update', $task) || Gate::check('delete', $task))
            <div class="dropdown card-options">
            <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons">more_vert</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">

                @can('update', $task)
                    <a href="{{ route('tasks.update', $task->id) }}" class="dropdown-item" data-params="closed=1" data-method="PATCH" data-remote="true" data-type="text">
                        {{__('Mark as done')}}
                    </a>

                    <a href="{{ route('tasks.edit',$task->id) }}" class="dropdown-item" data-remote="true" data-type="text">
                        {{__('Edit')}}
                    </a>
                @endcan
                <div class="dropdown-divider"></div>
                @can('delete', $task)
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
