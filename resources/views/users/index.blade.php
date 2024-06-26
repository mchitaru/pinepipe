
@foreach($users as $user)
@can('view', $user)
@php
$user_role = $user->getRole();
@endphp
@if(!isSet($role) || $user_role == $role)
<div class="card card-task mb-1">
    <div class="container row align-items-center">
        <div class="pl-2 position-absolute">
            <a href="#"  title={{$user->name}}>
                {!!Helpers::buildUserAvatar($user)!!}
            </a>
        </div>
        <div class="card-body p-2 pl-5">
            <div class="card-title col-xs-12 col-sm-5">
                <h6 data-filter-by="text">{{$user->name}}</h6>
                @if(\Auth::user()->isSuperAdmin())
                    <span class="badge badge-light mr-2">
                        {{($user->getCompany()->subscribed()&&$user->getCompany()->subscription()->plan)?$user->getCompany()->subscription()->plan->name:''}}
                    </span>
                    <p class="text-small">{{$user->created_at}}</p>
                @else
                <span class="badge badge-light mr-2">
                    {{__($user_role)}}
                </span>
                @endif
            </div>
            <div class="card-title col-xs-12 col-sm-5">
                <span class="d-flex align-items-center mb-1">
                    <i class="material-icons">email</i>
                    <a href="mailto:{{$user->email}}">
                        <span data-filter-by="text" class="text-small">
                            {{$user->email}}
                        </span>
                    </a>
                </span>
                @if(\Auth::user()->type=='super admin')
                <div class="d-flex align-items-center">
                    <span class="badge badge-light mr-2">
                        <i class="material-icons" title={{__("People")}}>people</i>
                        {{$user->totalCompanyUsers()}}
                    </span>
                    <span class="badge badge-light mr-2">
                        <i class="material-icons" title={{__("Projects")}}>folder</i>
                        {{$user->totalCompanyProjects()}}
                    </span>
                    <span class="badge badge-light mr-2">
                        <i class="material-icons" title={{__("Clients")}}>storefront</i>
                        {{$user->totalCompanyClients()}}
                    </span>
                </div>
                @endif
            </div>
            <div class="card-meta col-1 float-right">
            @if(\Auth::user()->isSuperAdmin() || $user->isCollaborator() || $user->isEmployee())
                <div class="dropdown card-options">
                    <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if($user->isCollaborator() || $user->isEmployee())
                            <a class="dropdown-item" href="{{ route('users.invite.store') }}" data-params="email={{$user->email}}" data-method="post" data-remote="true" data-type="text">
                                    <span>{{__('Resend invitation')}}</span>
                                </a>
                        @endif
                        @if(\Auth::user()->isSuperAdmin())
                            <a class="dropdown-item text-danger" href="{{ route('users.verify', $user->id) }}" data-remote="true" data-type="text">
                                <span>{{__('Resend verification')}}</span>
                            </a>
                            @can('delete', $user)
                            <a class="dropdown-item text-danger" href="{{ route('users.destroy', $user->id) }}" data-method="delete" data-remote="true" data-type="text">
                                <span>{{__('Delete')}}</span>
                            </a>
                            @endcan
                        @endif
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>
@endif
@endcan
@endforeach

@if(method_exists($users,'links'))
{{ $users->links() }}
@endif
