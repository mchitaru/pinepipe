<a class="navbar-brand float-left" href="{{ route('home') }}">
    <img alt="BaseCRM" width=30 src="{{ asset('assets/img/logo.svg') }}" />
</a>
<div class="dropdown float-right">
    <a href="{{route('users.notifications')}}" id="notification-bell" name="notification-bell" role="button" data-method="post" data-remote="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
        <i class="material-icons">{{$user->unreadNotifications->count()?'notifications':'notifications_none'}}</i>
    </a>
    
    <div class="dropdown-menu">
        @if(!$user->unreadNotifications->isEmpty())
            @foreach ($user->unreadNotifications as $notification)
                <a class="dropdown-item" href="#">
                    {{$notification->type}}
                </a>
            @endforeach            
        @else
            {{__('Nothing to see here')}}
        @endif
    </div>
</div>
