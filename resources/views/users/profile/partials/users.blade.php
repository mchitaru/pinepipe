<div class="row align-items-center">
    <div class="col p-2">
        <div class="media align-items-center">
        <div class="media-body ml-2">
            @can('create', 'App\User')
                <a href="{{ route('users.invite.create') }}" class="btn btn-primary" data-remote="true" data-type="text">
                    {{__('Invite collaborators')}}
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