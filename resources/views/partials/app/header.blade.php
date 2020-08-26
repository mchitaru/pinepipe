<div class="navbar bg-white sticky-top align-items-center d-none d-lg-flex" style="z-index: 1019">
    <div class="d-flex align-items-center">
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-sm align-items-center mx-1" title="{{__('Back')}}">
            <div class="icon">
                <i class="material-icons align-middle">arrow_back_ios</i>
            </div>
        </a>
        @if(\Auth::user()->type !='super admin')
        <div class="input-group input-group-light">
            <input type="search" class="form-control form-control-light expandable search-element" placeholder="{{__('Search...')}}" aria-label="Search app">
        </div>
        @endif
    </div>
    <div class="d-flex align-items-center w-60 justify-content-end">
        @if(\Auth::user()->type!='super admin')
        <div class="dropdown text-center align-items-center border-left pl-2 border-right pr-2">
            @include('partials.app.timesheets')
        </div>
        @endif
        <div class="dropdown">
            <button href="#" class="btn dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{$_user->name}}">
                {!!Helpers::buildUserAvatar($_user, 32, 'rounded')!!}
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{route('profile.edit', \Auth::user()->handle())}}">
                    {{__('My Profile')}}
                </a>
                <div class="dropdown-divider"></div>
                @if(\Auth::user()->type!='client' && (Gate::check('view user') || Gate::check('view permission')))
                    @if(Gate::check('view user'))
                        <a class="dropdown-item" href="{{ route('users.index') }}">{{__('Users')}}</a>
                    @endif
                    @if(Gate::check('view permission'))
                        <a class="dropdown-item" href="{{ route('roles.index') }}">{{__('Roles')}}</a>
                    @endif
                    <div class="dropdown-divider"></div>
                @endif

                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                    {{__('Logout')}}
                </a>
                <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
</div>
