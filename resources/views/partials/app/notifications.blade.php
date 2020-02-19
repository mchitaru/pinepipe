<div class="dropdown float-right">
    <a href="{{route('users.notifications')}}" id="notification-bell" name="notification-bell" role="button" data-method="post" data-remote="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
        <i class="material-icons">{{$user->unreadNotifications->count()?'notifications':'notifications_none'}}</i>
    </a>
    
    <ul class="dropdown-menu pre-scrollable">
        @if(!$user->notifications->isEmpty())

            {{-- <li>
                <a class="dropdown-item" href="{{route('projects.index')}}/#tasks">
                    {!!__('See all tasks')!!}
                    <i class="material-icons text-small">arrow_forward</i>
                </a>    
            </li>
            <div class="dropdown-divider"></div> --}}

            @foreach ($user->notifications as $notification)
                @if($notification->type == 'App\Notifications\TaskOverdueAlert')
                    @foreach ($notification->data as $key=>$task)
                    <li>
                        <a class="dropdown-item" href="{{route('tasks.show', $key)}}" data-remote="true" data-type="text">
                                {!!__('Task ').'<u>'.$task.'</u>'.__(' is overdue')!!}
                                <small class="badge badge-info">{{ $notification->created_at->diffForHumans() }}</small>
                        </a>
                    </li>
                    @endforeach
                @elseif($notification->type == 'App\Notifications\PaymentPlanExpiredAlert')
                    @foreach ($notification->data as $key=>$message)
                    <li>
                        <a class="dropdown-item" href="{{route('profile.show')}}/#billing">
                                {!! $message !!}
                                <small class="badge badge-info">{{ $notification->created_at->diffForHumans() }}</small>
                        </a>
                    </li>
                    @endforeach
            @endif
            @endforeach                
        @else
            <li>{{__('No notifications.')}}</li>
        @endif
    </ul>    
</div>
