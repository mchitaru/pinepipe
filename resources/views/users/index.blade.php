
@foreach($users as $user)
<div class="card card-task mb-1">
    <div class="container row align-items-center">
        <div class="pl-2 position-absolute">
            <a href="#"  title={{$user->name}}>
                {!!Helpers::buildUserAvatar($user)!!}
            </a>
        </div>
        <div class="card-body p-2 pl-5">
            <div class="card-title col-xs-12 col-sm-3">
                @if(Gate::check('edit user'))
                    <a href="{{ $user->enabled?route('users.edit',$user->id):'#' }}" data-remote="true" data-type="text">
                        <h6 data-filter-by="text">{{$user->name}}</h6>
                    </a>
                @else
                    <h6 data-filter-by="text">{{$user->name}}</h6>
                @endif
                <p class="text-small">{{$user->type}}</p>
                @if(\Auth::user()->type=='super admin')
                    <p class="text-small">{{$user->created_at}}</p>
                @endif
            </div>
            <div class="card-title col-xs-12 col-sm-5">
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
                    <span class="badge badge-light mr-2">
                        <i class="material-icons" title={{__("Collaborators")}}>people</i>
                        {{$user->total_company_user()}}
                    </span>
                    <span class="badge badge-light mr-2">
                        <i class="material-icons" title={{__("Projects")}}>folder</i>
                        {{$user->total_company_project()}}
                    </span>
                    <span class="badge badge-light mr-2">
                        <i class="material-icons" title={{__("Clients")}}>storefront</i>
                        {{$user->total_company_client()}}
                    </span>
                    @else
                    <span class="badge badge-light mr-2">
                        <i class="material-icons" title={{__("Projects")}}>folder</i>
                        {{$user->user_projects_count()}}
                    </span>
                    <span class="badge badge-light mr-2">
                        <i class="material-icons" title={{__("Tasks")}}>playlist_add_check</i>
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

                            {{-- Add items to menu only if user not deleted !!--}}
                            @if(!$user->trashed())
                                @can('edit user')
                                <a class="dropdown-item" href="{{ route('users.edit',$user->id) }}" data-remote="true" data-type="text">
                                    <span>{{__('Edit')}}</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                @endcan
                            @endif

                            @can('delete user')
                                @if(!$user->trashed())
                                    @if(\Auth::user()->type=='super admin')
                                        <a class="dropdown-item text-danger" href="{{ route('users.destroy', $user->id) }}" data-method="delete" data-remote="true" data-type="text">
                                            <span>{{__('Delete')}}</span>
                                        </a>
                                    @else
                                        <a class="dropdown-item text-danger" href="{{ route('users.update', $user->id) }}" data-method="patch" data-remote="true" data-type="text">
                                            <span>{{__('Delete')}}</span>
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('users.update', $user->id) }}" class="dropdown-item text-danger" data-params="archived=0" data-method="PATCH" data-remote="true" data-type="text">
                                        {{__('Restore')}}
                                    </a>
                                @endif
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

@if(method_exists($users,'links'))
{{ $users->links() }}
@endif
