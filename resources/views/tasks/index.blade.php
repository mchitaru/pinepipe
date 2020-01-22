@php
    use Carbon\Carbon;
    use App\Project;

    $current_user=\Auth::user();
    $profile=asset(Storage::url('avatar/'));
@endphp

@foreach($stages as $stage)

@if(\Auth::user()->type =='client' || \Auth::user()->type =='company')
    @php $tasks =$stage->tasks($project_id)    @endphp
@else
    @php $tasks =$stage->tasks($project_id)    @endphp
@endif

    <div class="card-list">
        <div class="card-list-head">
        <h6>{{$stage->name}} ({{ count($tasks) }})</h6>
        <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">more_vert</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="#">Rename</a>
            <a class="dropdown-item text-danger" href="#">Archive</a>
            </div>
        </div>
        </div>
        <div class="card-list-body">

        @foreach($tasks as $task)

        @php
            $total_subtask = $task->taskTotalCheckListCount();
            $completed_subtask = $task->taskCompleteCheckListCount();

            $task_percentage=0;
            if($total_subtask!=0){
                $task_percentage = intval(($completed_subtask / $total_subtask) * 100);
            }

            $label = Project::getProgressColor($task_percentage);
        @endphp

            <div class="card card-task">
                <div class="progress">
                <div class="progress-bar {{$label}}" id="taskProgress{{$task->id}}" role="progressbar" style="width: {{$task_percentage}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="card-body">
                <div class="card-title">

                    <a href="{{route('tasks.show',$task->id)}}#task" class="text-body" data-remote="true" data-type="text">
                        <h6 data-filter-by="text">{{$task->title}}</h6>
                    </a>

                    <span class="badge badge-secondary">
                        @if($task->priority =='low')
                                <div class="label label-soft-success"> {{ $task->priority }}</div>
                            @elseif($task->priority =='medium')
                                <div class="label label-soft-warning"> {{ $task->priority }}</div>
                            @elseif($task->priority =='high')
                                <div class="label label-soft-danger"> {{ $task->priority }}</div>
                            @endif
                     </span>

                     <p>
                        <span class="text-small">{{__('Due')}} {{ Carbon::parse($task->due_date)->diffForHumans() }}</span>
                    </p>
                </div>
                <div class="card-title">
                    <div class="row align-items-center">
                        <i class="material-icons">folder</i>
                        <span data-filter-by="text" class="text-small" data-toggle="tooltip" data-original-title="{{__('Project')}}">{{ !empty($task->project) ? $task->project->name : '--' }}</span>
                    </div>
                </div>
                <div class="card-title">
                    <div class="row align-items-center">
                        <i class="material-icons">person</i>
                        <span data-filter-by="text" class="text-small" data-toggle="tooltip" data-original-title="{{__('Client')}}">{{ !empty($task->project) ? $task->project->client()->name : '--' }}</span>
                    </div>
                </div>
                <div class="card-meta">
                    <ul class="avatars">

                    <li>
                        @if(!empty($task->task_user))
                        <a href="#" data-toggle="tooltip" title="" data-original-title="{{(!empty($task->task_user)?$task->task_user->name:'')}}">
                            <img alt="{{$task->task_user->name}}" {!! empty($task->task_user->avatar) ? "avatar='".$task->task_user->name."'" : "" !!} class="avatar" src="{{asset(Storage::url("avatar/".$task->task_user->avatar))}}" data-filter-by="alt"/>
                        </a>
                        @endif
                    </li>

                    </ul>
                    <div class="d-flex align-items-center">
                    <i class="material-icons">playlist_add_check</i>
                    <p class="small @if($total_subtask==0) text-muted @endif @if($completed_subtask==$total_subtask && $completed_subtask!=0) text-success @else text-danger @endif">
                        <span>{{$completed_subtask}}/{{$total_subtask}}</span>
                    </p>
                    </div>
                    @if(Gate::check('edit task') || Gate::check('delete task'))
                        <div class="dropdown card-options">
                        <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">

                            <a class="dropdown-item" href="#">Mark as done</a>
                            @can('edit task')
                                <a href="#" class="dropdown-item" data-url="{{ route('tasks.edit',$task->id) }}" data-ajax-popup="true" data-title="{{__('Edit')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                    {{__('Edit')}}
                                </a>
                            @endcan
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#">Archive</a>
                            @can('delete task')
                                <a href="{{route('tasks.destroy',$task->id)}}" class="dropdown-item text-danger" data-method="delete" data-remote="true" data-type="text">
                                    {{__('Delete')}}
                                </a>
                                {{-- <a href="#" class="dropdown-item text-danger" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('task-delete-form-{{$task->id}}').submit();">
                                    {{__('Delete')}}
                                </a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['tasks.destroy', $task->id],'id'=>'task-delete-form-'.$task->id]) !!}
                                {!! Form::close() !!} --}}
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
