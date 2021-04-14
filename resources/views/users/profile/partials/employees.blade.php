@php
$role = 'employee';
@endphp
<div class="row align-items-center">
    <div class="col p-2">
        <div class="media align-items-center">
            <div class="media-body ml-2">
                @can('create', 'App\User')
                    <a href="{{ route('users.invite.create', [$role]) }}" class="btn btn-primary" data-remote="true" data-type="text">
                        <span>{{__('Invite employee')}}</span>
                        <i class="material-icons align-middle">info</i>
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>
<div class="row align-items-center">
    <div class="col p-2">
        @include('users.index')
    </div>        
</div>