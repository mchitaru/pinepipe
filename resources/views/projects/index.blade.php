@php
use App\Http\Helpers;
@endphp

@foreach ($projects as $project)

@php
    $permissions=$project->client_project_permission();
    $perArr=(!empty($permissions)? explode(',',$permissions->permissions):[]);

    $project_last_stage = ($project->project_last_stage($project->id)? $project->project_last_stage($project->id)->id:'');

    $total_task = $project->project_total_task($project->id);
    $completed_task=$project->project_complete_task($project->id,$project_last_stage);
    
    $percentage=0;
    if($total_task!=0){
        $percentage = intval(($completed_task / $total_task) * 100);
    }

    $label='';
    if($percentage<=15){
        $label='bg-danger';
    }else if ($percentage > 15 && $percentage <= 33) {
        $label='bg-warning';
    } else if ($percentage > 33 && $percentage <= 70) {
        $label='bg-primary';
    } else {
        $label='bg-success';
    }

@endphp

    <div class="col-lg-6">
        <div class="card card-project">
            <div class="progress">
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{$percentage}}%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <div class="card-body">
                @if(Gate::check('edit project') || Gate::check('delete project') || Gate::check('create user'))
                    @if($project->is_active)
                        <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="project-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                            @can('edit project')
                                <a class="dropdown-item" href="{{ route('projects.edit', $project->id) }}" data-remote="true" data-type="text">
                                    {{__('Edit')}}
                                </a>
                            @endcan
                            @can('manage invite user')
                                <a class="dropdown-item" href="{{ route('project.invite', $project->id) }}" data-remote="true" data-type="text">
                                    {{__('Add User')}}
                                </a>
                            @endcan
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger disabled" href="#">{{__('Archive')}}</a>
                            @can('delete project')
                                <a class="dropdown-item text-danger" href="{{ route('projects.destroy', $project->id) }}" data-method="delete" data-remote="true" data-type="text">
                                    {{__('Delete')}}
                                </a>
                            @endcan
                            </div>
                        </div>
                    @endif
                @endif
                <div class="card-title d-flex justify-content-between align-items-center">
                    @can('show project')
                    @if($project->is_active)
                        <a href="{{ route('projects.show', $project->id) }}">
                            <h5 data-filter-by="text">{{ $project->name }}</h5>
                        </a>
                    @else
                        <a href="#">
                            <h5 data-filter-by="text">{{ $project->name }}</h5>
                        </a>
                    @endif
                    @else
                        <a href="#">
                            <h5 data-filter-by="text">{{ $project->name }}</h5>
                        </a>
                    @endcan
                    @foreach($project_status as $key => $status)
                    @if($key== $project->status)
                        <span class="badge badge-secondary">{{ $status}}</span>
                    @endif
                    @endforeach
                </div>
                <ul class="avatars">

                    @foreach($project->users as $user)
                    <li>
                        @if($project->is_active)
                            <a href="{{ route('users.index', $user->id) }}" data-toggle="tooltip" title="{{(!empty($user)?$user->name:'')}}">
                        @endif
                            {!!Helpers::buildAvatar($user)!!}
                        @if($project->is_active)
                        </a>
                        @endif
                    </li>
                    @endforeach
                </ul>
                <div class="card-meta d-flex justify-content-between">
                    <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Completed Tasks')}}">
                        <i class="material-icons mr-1">playlist_add_check</i>
                        @if($project->is_active)
                        <a  href="{{ route('projects.show', $project->id) }}">{{$completed_task}}/{{$total_task}}</a>
                        @else
                        <a  href="#">{{$completed_task}}/{{$total_task}}</a>
                        @endif
                    </div>
                    <div class="d-flex align-items-center" data-toggle="tooltip" title="{{__('Client')}}">
                        <i class="material-icons mr-1">person</i>
                        @if($project->is_active && !empty($project->client))
                        <a href="{{ route('clients.show', $project->client->id) }}" data-filter-by="text">{{(!empty($project->client)?$project->client->name:'')}}</a>
                        @else
                        <a data-filter-by="text">{{(!empty($project->client)?$project->client->name:'')}}</a>
                        @endif
                    </div>
                    <span class="text-small" data-filter-by="text">{{__('Due on ')}}
                        {{ \Auth::user()->dateFormat($project->due_date) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@endforeach
