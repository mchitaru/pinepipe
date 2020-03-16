@php
$user=\Auth::user();
$logo = asset(Storage::url('logo/'));

$currantLang = $user->currentLanguage();
$languages=$user->languages();
@endphp

<div class="navbar navbar-expand-lg bg-dark navbar-dark sticky-top">
<a class="navbar-brand" href="{{ route('home') }}">
    <img alt="Pipeline" width=30 src="{{ asset('assets/img/logo.svg') }}" />
</a>

<div class="mx-lg-2">
    @include('partials.app.notifications')
    @include('partials.app.timesheets')
</div>

<div class="d-flex align-items-center">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
</div>
<div class="collapse navbar-collapse justify-content-between" id="navbar-collapse">
    <ul class="navbar-nav">

    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">{{__('Home')}}</a>
    </li>

    @if(\Auth::user()->type!='super admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('calendar.index') }}">{{__('Calendar')}}</a>
    </li>
    @endif

    @if(\Auth::user()->type=='super admin')
        @can('create language')
        <li class="nav-item">
            <a class="nav-link" href="{{url('/languages')}}">{{__('Languages')}}</a>
        </li>
        @endcan

        @can('manage plan')
        <li class="nav-item">
            <a class="nav-link" href="{{route('plans.index')}}">{{__('Price Plans')}}</a>
        </li>
        @endcan

        @can('manage order')
        @endcan
    @endif

    @if(\Auth::user()->type!='super admin' && Gate::check('manage client'))
    <li class="nav-item">

        <div class="dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="nav-dropdown-2">{{__('Clients')}}</a>
            <div class="dropdown-menu">

                @can('manage contact')
                    <a class="dropdown-item" href="{{ route('contacts.index') }}">{{__('Contacts')}}</a>
                @endif

                @can('manage lead')
                    <a class="dropdown-item" href="{{ route('leads.board') }}">{{__('Leads')}}</a>
                @endif

                <a class="dropdown-item" href="{{ route('clients.index') }}">{{__('Clients')}}</a>

                <a class="dropdown-item disabled" href="#">{{__('Proposals')}}</a>

                <a class="dropdown-item disabled" href="#">{{__('Contracts')}}</a>
            </div>
        </div>
    </li>
    @endif

    @if(Gate::check('manage project') || Gate::check('manage task'))
    <li class="nav-item">
        <div class="dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="nav-dropdown-2">{{__('Projects')}}</a>
        <div class="dropdown-menu">

            @can('manage project')
                <a class="dropdown-item" href="{{ route('projects.index') }}">{{__('Projects')}}</a>
            @endcan

            @can('manage task')
                <a class="dropdown-item" href="{{ route('tasks.board') }}">{{__('Tasks')}}</a>
            @endcan

        </div>
        </div>
    </li>
    @endif

    @if((Gate::check('manage product') || Gate::check('manage invoice') || Gate::check('manage expense') || Gate::check('manage payment') || Gate::check('manage tax')) || \Auth::user()->type=='client')
    <li class="nav-item">

        <div class="dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="nav-dropdown-2">{{__('Finance')}}</a>
            <div class="dropdown-menu">
                @if(Gate::check('manage invoice') || \Auth::user()->type=='client')
                    <a class="dropdown-item" href="{{ route('invoices.index') }}">{{__('Invoices')}}</a>
                @endcan

                @if(Gate::check('manage expense') || \Auth::user()->type=='client')
                    <a class="dropdown-item" href="{{ route('expenses.index') }}">{{__('Expenses')}}</a>
                @endif
            </div>
        </div>
    </li>
    @endif

    @if(Gate::check('manage project') || \Auth::user()->type!='super admin')
    <li class="nav-item">

        <div class="dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="nav-dropdown-2">{{__('Reports')}}</a>
        <div class="dropdown-menu">

            <a class="dropdown-item disabled" href="#">{{__('Timesheets')}}</a>

        </div>
        </div>

    </li>
    @endif

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

        @if(Gate::check('create contact') ||
            Gate::check('create project') ||
            Gate::check('create tasks'))
        <div class="dropdown mx-lg-2">
            <button class="btn btn-primary btn-block dropdown-toggle" type="button" id="newContentButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{__('Add New')}}
            </button>
            <div class="dropdown-menu">
                @can('create contact')
                    <a class="dropdown-item" href="{{ route('contacts.create') }}" data-remote="true" data-type="text">{{__('Contact')}}</a>
                @endcan
                @can('create project')
                    <a class="dropdown-item" href="{{ route('projects.create') }}" data-remote="true" data-type="text">{{__('Project')}}</a>
                @endcan
                @can('create task')
                    <a class="dropdown-item" href="{{ route('tasks.create') }}" data-remote="true" data-type="text">{{__('Task')}}</a>
                @endcan
            </div>
        </div>
        @endif

        <!-- Profile menu --->
        <div class="dropdown mx-lg-2 float-right">
            <div class="dropdown dropdown-toggle">
            <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {!!Helpers::buildUserAvatar($user, 36, 'round')!!}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @can('manage account')
                <a class="dropdown-item" href="{{route('profile.show')}}">
                    {{__('Account Settings')}}
                </a>
                @endcan

                <div class="dropdown-divider"></div>

                @if(\Auth::user()->type!='client' && (Gate::check('manage user') || Gate::check('manage role')))
                @if(Gate::check('manage user'))
                    <a class="dropdown-item" href="{{ route('users.index') }}">{{__('Users')}}</a>
                @endif
                @if(Gate::check('manage role'))
                    <a class="dropdown-item" href="{{ route('roles.index') }}">{{__('Roles')}}</a>
                @endif
                <div class="dropdown-divider"></div>
                @endif

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
