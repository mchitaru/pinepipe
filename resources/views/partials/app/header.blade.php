@php
    $users=\Auth::user();
    $profile=asset(Storage::url('avatar/'));
    $logo=asset(Storage::url('logo/'));

    $currantLang = $users->currentLanguage();
    $languages=$users->languages();

@endphp
<div class="navbar navbar-expand-lg bg-dark navbar-dark sticky-top">
<a class="navbar-brand" href="{{ route('home') }}">
    <img alt="Pipeline" width=30 src="{{ asset('assets/img/logo.svg') }}" />
</a>
<div class="d-flex align-items-center">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
</div>
<div class="collapse navbar-collapse justify-content-between" id="navbar-collapse">
    <ul class="navbar-nav">

    <li class="nav-item">

        <a class="nav-link" href="{{ route('home') }}">Workspace</a>

    </li>

    <li class="nav-item">

        <div class="dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="nav-dropdown-2">Clients</a>
            <div class="dropdown-menu">

                <a class="dropdown-item" href="{{ route('clients.index') }}">Clients</a>

                <a class="dropdown-item" href="{{ route('clients.index') }}">Contacts</a>

                <a class="dropdown-item" href="{{ route('kanban') }}">Leads</a>

                <a class="dropdown-item" href="{{ route('clients.index') }}">Activity</a>

            </div>
        </div>
    </li>

    <li class="nav-item">

        <div class="dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="nav-dropdown-2">Projects</a>
        <div class="dropdown-menu">

            @if(Gate::check('manage project'))
                <a class="dropdown-item" href="{{ route('projects.index') }}">{{__('Projects')}}</a>
            @endif

            <a class="dropdown-item" href="{{ route('project') }}">Project</a>

            <a class="dropdown-item" href="{{ route('task') }}">Task</a>

            <a class="dropdown-item" href="{{ route('kanban') }}">Kanban</a>

        </div>
        </div>

    </li>

    <li class="nav-item">

        <div class="dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="nav-dropdown-2">Finance</a>
        <div class="dropdown-menu">

            <a class="dropdown-item" href="{{ route('project') }}">Proposals</a>

            <a class="dropdown-item" href="{{ route('project') }}">Contracts</a>

            <a class="dropdown-item" href="{{ route('project') }}">Invoices</a>

            <a class="dropdown-item" href="{{ route('project') }}">Expenses</a>

        </div>
        </div>

    </li>

    <li class="nav-item">

        <div class="dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="nav-dropdown-2">Reports</a>
        <div class="dropdown-menu">

            <a class="dropdown-item" href="{{ route('project') }}">Timesheets</a>

        </div>
        </div>

    </li>

    </ul>
    <div class="d-lg-flex align-items-center">
        @if(\Auth::user()->type !='super admin')
        <div class="mx-lg-2">
            <form class="form-inline my-lg-0 my-2" method="post" autocomplete="off">
                @csrf
                <div class="input-group input-group-dark input-group-round">
                    <input type="search" class="form-control form-control-dark border-0" placeholder="Search" aria-label="Search app" id="search-element">
                </div>
            </form>
        </div>
        @endif

    <div class="dropdown mx-lg-2">
        <button class="btn btn-primary btn-block dropdown-toggle" type="button" id="newContentButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Add New
        </button>
        <div class="dropdown-menu">
        <a class="dropdown-item" href="#">Team</a>
        <a class="dropdown-item" href="#">Project</a>
        <a class="dropdown-item" href="#">Task</a>
        </div>
    </div>
    @if(\Auth::user()->type!='client')
    <!-- Settings menu --->
    <div class="dropdown mx-lg-2">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">settings</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            @if(\Auth::user()->type=='super admin' || Gate::check('manage user'))
                @can('manage user')
                <a class="dropdown-item" href="{{ route('users.index') }}">{{__('Users')}}</a>
                @endcan
            @endif
            @if(Gate::check('manage role'))
                <a class="dropdown-item" href="{{ route('roles.index') }}">{{__('User Roles')}}</a>
            @endif
            <div class="dropdown-divider"></div>
            @if(Gate::check('manage plan'))
                <a href="{{ route('plans.index') }}" class="dropdown-item">
                    <span class="title">{{__('Price Plans')}}</span>
                </a>
            @endif
            @if(Gate::check('manage order'))
                <a href="{{ route('order.index') }}" class="dropdown-item">
                    <span class="title">{{__('Orders')}}</span>
                </a>
            @endif
        </div>
    </div>
    @endif
    <!-- Profile menu --->
    <div class="dropdown mx-lg-2">
        <div class="dropdown dropdown-toggle">
        <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img alt="{{$users->name}}" title="{{$users->name}}" src="{{(!empty($users->avatar)? $profile.'/'.$users->avatar : $profile.'/avatar.png')}}" class="avatar" />
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            @can('manage account')
            <a href="{{route('profile',$users->id)}}" class="dropdown-item">
                {{__('Profile')}}
            </a>
            @endcan
            <div class="dropdown-divider"></div>
            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                {{__('Logout')}}
            </a>
            <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
        </div>
    </div>
    </div>
</div>
</div>
