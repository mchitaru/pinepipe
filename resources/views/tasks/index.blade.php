@php
    use Carbon\Carbon;
    use App\Project;
    use App\ProjectStage;
    use App\Http\Helpers;

    $current_user=\Auth::user();
@endphp

@foreach($stages as $stage)

@php 

    $tasks = $stage->getTasksByUserType($project_id)    
    
@endphp

    <div class="card-list">
        <div class="card-list-head">
        <h6>{{$stage->name}} ({{ count($tasks) }})</h6>
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

            <div class="card card-task">
                <div class="progress">
                <div class="progress-bar {{$label}}" id="taskProgress{{$task->id}}" role="progressbar" style="width: {{$task_percentage}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="card-body">
                <div class="card-title col-xs-12 col-sm-4">

                    <a href="{{route('tasks.show',$task->id)}}#task" class="text-body" data-remote="true" data-type="text">
                        <h6 data-filter-by="text">{{$task->title}}</h6>
                    </a>

                    
                    @if($task->priority =='low')
                        <span class="badge badge-success"> {{ $task->priority }}</span>
                    @elseif($task->priority =='medium')
                        <span class="badge badge-warning"> {{ $task->priority }}</span>
                    @elseif($task->priority =='high')
                        <span class="badge badge-danger"> {{ $task->priority }}</span>
                    @endif

                     <p>
                        <span class="text-small">{{__('Due')}} {{ Carbon::parse($task->due_date)->diffForHumans() }}</span>
                    </p>
                </div>
                <div class="card-title d-none d-xl-block col-xs-12 col-sm-4">
                    <div class="row align-items-center" data-toggle="tooltip" title="{{__('Project')}}">
                        <i class="material-icons">folder</i>
                        <span data-filter-by="text" class="text-small">{{ !empty($task->project) ? $task->project->name : '---' }}</span>
                    </div>
                    <div class="row align-items-center" data-toggle="tooltip" title="{{__('Client')}}">
                        <i class="material-icons">person</i>
                        <span data-filter-by="text" class="text-small">{{ !empty($task->project) ? $task->project->client->name : '---' }}</span>
                    </div>
                </div>
                <div class="card-title col-xs-12 col-sm-3 text-right">

                    <ul class="avatars">

                        @foreach($task->users as $user)
                        <li>
                            <a href="{{ route('users.index',$user->id) }}" data-toggle="tooltip" title="{{$user->name}}">
                                {!!Helpers::buildAvatar($user)!!}
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
                                {!! Form::open(['method' => 'PATCH', 'route' => ['tasks.update', $task->id]]) !!}
                                {!! Form::hidden('stage_id', ProjectStage::all()->last()->id) !!}
                                {!! Form::submit(__('Mark as done'), array('class'=>'dropdown-item text-danger')) !!}
                                {!! Form::close() !!}

                                {{-- <a href="{{route('tasks.update',$task->id)}}" data-remote="true" data-method="patch" data-params="{&quot;status&quot;:&quot;done&quot;,&quot;stage&quot;:&quot;4&quot;}"  class="dropdown-item text-danger">
                                    {{__('Mark as done')}}
                                </a> --}}

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
