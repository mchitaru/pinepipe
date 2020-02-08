@php
use App\Http\Helpers;
@endphp

@foreach($users as $user)
<div class="card card-task mb-1" style="min-height: 67px;">
    <div class="container row align-items-center">
        <div class="pl-2 position-absolute">
            <a href="#" data-toggle="tooltip" title={{$user->name}}>
                {!!Helpers::buildAvatar($user)!!}
            </a>
        </div>
        <div class="card-body p-2 pl-5">
            <div class="card-title col-xs-12 col-sm-4">
                @can('edit user')
                    <a class="dropdown-item" href="{{ $user->enabled?route('users.edit',$user->id):'#' }}" data-remote="true" data-type="text">
                @endcan
                    <h6 data-filter-by="text">{{$user->name}}</h6>
                @can('edit user')
                    </a>
                @endcan
                <span class="text-small">{{$user->type}}</span>
            </div>
            <div class="card-title col-xs-12 col-sm-4">
                <span class="d-flex align-items-center">
                    <i class="material-icons">email</i>
                    <a href="mailto:kenny.tran@example.com">
                        <span data-filter-by="text" class="text-small">
                            {{$user->email}}
                        </span>
                    </a>
                </span>
                    @if($user->trashed())
                        <span class="badge badge-danger">{{__('Deleted')}}</span>
                    @endif
            </div>
            <div class="card-meta col-2">
                <div class="d-flex align-items-center justify-content-end">
                    @if(\Auth::user()->type=='super admin')
                    <span class="badge badge-secondary mr-2">
                        <i class="material-icons" title="Users">people</i>
                        {{$user->total_company_user($user->id)}}
                    </span>
                    <span class="badge badge-secondary mr-2">
                        <i class="material-icons" title="Projects">folder</i>
                        {{$user->total_company_project($user->id)}}
                    </span>
                    <span class="badge badge-secondary mr-2">
                        <i class="material-icons" title="Clients">storefront</i>
                        {{$user->total_company_client($user->id)}}
                    </span>
                    @else
                    <span class="badge badge-secondary mr-2">
                        <i class="material-icons" title="Projects">folder</i>
                        {{$user->user_projects_count()}}
                    </span>
                    <span class="badge badge-secondary mr-2">
                        <i class="material-icons" title="Tasks">playlist_add_check</i>
                        {{$user->user_tasks_count()}}
                    </span>
                    @endif
                </div>
            </div>
            <div class="dropdown card-options">
                    @if($user->enabled)
                        <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right">
                            @can('edit user')
                            @if(!$user->trashed())
                            <a class="dropdown-item" href="{{ route('users.edit',$user->id) }}" data-remote="true" data-type="text">
                                <span>{{__('Edit')}}</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            @endif
                            @endcan
                            @can('delete user')

                            @if(!$user->trashed())
                                <a class="dropdown-item text-danger" href="{{ route('users.update', $user->id) }}" data-method="patch" data-remote="true" data-type="text">
                                    <span>{{__('Delete')}}</span>
                                </a>
                            @else
                                {!! Form::open(['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!}
                                {!! Form::submit(__('Restore'), array('class'=>'dropdown-item text-danger')) !!}
                                {!! Form::close() !!}
                            @endif
                            {{-- <a class="dropdown-item text-danger" href="{{ route('users.destroy', $user->id) }}" data-method="delete" data-remote="true" data-type="text">
                                <span>{{__('Delete')}}</span>
                            </a> --}}
                            @endcan
                        </div>
                    @else
                        <i class="material-icons">lock</i>
                    @endif
                </div>
            </div>
    </div>
</div>
@endforeach
