
<div class="card">
<div class="card-body">
    <div class="row align-items-center">
    <div class="col">
        <div class="media align-items-center">
        <img alt="Calendar" width=32 height=32 src="{{ asset('assets/img/logo-integration-google-calendar.png') }}" />
        <div class="media-body ml-2">
            <span class="h6 mb-0 d-block">
                {{__('Google Calendar')}} 
                <span class="badge badge-warning" data-filter-by="text"></span>
            </span>
            <span class="text-small text-muted">{{__('Linked account:')}} {{ \Auth::user()->googleAccounts->isEmpty()?__('none'):\Auth::user()->googleAccounts->first()->name}}</span>
        </div>
        </div>
    </div>
        <div class="col-auto">
            @if(\Auth::user()->googleAccounts->isEmpty())
            <a href="{{ route('google.store') }}" class="btn btn-primary">
                {{__('Link')}}
            </a>
            @else
            <a href="{{ route('google.destroy', \Auth::user()->googleAccounts->first()) }}" class="btn btn-danger" data-method="delete" data-remote="true" data-type="text">
                {{__('Revoke')}}
            </a>
            @endif
        </div>
    </div>
</div>
</div>