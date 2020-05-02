@php
use Carbon\Carbon;

$user=\Auth::user();
$logo = asset(Storage::url('logo/'));

$languages=$user->languages();

@endphp

<div class="navbar navbar-expand-lg bg-dark navbar-dark sticky-top" style="overflow:visible;">
    <div class="w-100 d-none d-lg-block">
        <a class="navbar-brand float-left" href="{{ route('home') }}">
            <img alt="Pinepipe" width=30 src="{{ asset('assets/img/logo.svg') }}" />
        </a>
        @include('partials.app.notifications')
        @if(\Auth::user()->type!='super admin')
            @include('partials.app.timesheets')
        @endif
    </div>
    <div class="d-lg-none">
        <a class="navbar-brand float-left" href="{{ route('home') }}">
            <img alt="Pinepipe" width=30 src="{{ asset('assets/img/logo.svg') }}" />
        </a>
        @include('partials.app.notifications')
        @if(\Auth::user()->type!='super admin')
            @include('partials.app.timesheets')
        @endif
    </div>
    <div class="d-flex align-items-center">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-block d-lg-none ml-2">
            <div class="dropdown">
            <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {!!Helpers::buildUserAvatar($user, 36, 'round')!!}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{route('profile.show')}}">
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
            </div>
            </div>
        </div>
    </div>
    <div class="collapse navbar-collapse flex-column" id="navbar-collapse">
    <ul class="navbar-nav d-lg-block">

        <li class="nav-item">
            <a class="nav-link {{(empty(Request::segment(1)) || Request::segment(1) == 'home')?' active':''}}" href="{{ route('home') }}">{{__('Home')}}</a>
        </li>

        @if(\Auth::user()->type!='super admin')
        <li class="nav-item">
            <a class="nav-link {{(Request::segment(1) == 'calendar')?' active':''}}" href="{{ route('calendar.index') }}">{{__('Calendar')}}</a>
        </li>
        @endif

        @if(\Auth::user()->type=='super admin')
            <li class="nav-item">
                <a class="nav-link" href="{{url('/languages')}}">{{__('Languages')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('plans.index')}}">{{__('Price Plans')}}</a>
            </li>
        @endif

        @can('view client')

        <li class="nav-item ">
            <a class="nav-link {{(Request::segment(1) == 'clients')?' active':''}}" href="{{ route('clients.index') }}">{{__('Clients')}}</a>
        </li>
        @endcan

        @if(Gate::check('view lead') || Gate::check('view contact'))
            <li class="nav-item">

            <a class="nav-link {{(Request::segment(1) == 'contacts' || Request::segment(1) == 'leads')?' active':''}}" href="#" data-toggle="collapse" aria-expanded="{{(Request::segment(1) == 'contacts' || Request::segment(1) == 'leads')?'true':'false'}}" data-target="#submenu-2" aria-controls="submenu-2">{{__('Sales')}}</a>
            <div id="submenu-2" class="{{(Request::segment(1) == 'contacts' || Request::segment(1) == 'leads')?'':'collapse'}}">
                <ul class="nav nav-small flex-column">

                @can('view lead')
                <li class="nav-item">
                    <a class="nav-link {{(Request::segment(1) == 'leads')?' active':''}}" href="{{ route('leads.board') }}">{{__('Leads')}}</a>
                </li>
                @endif
                
                @can('view contact')
                <li class="nav-item">
                    <a class="nav-link {{(Request::segment(1) == 'contacts')?' active':''}}" href="{{ route('contacts.index') }}">{{__('Contacts')}}</a>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link disabled" href="#">{{__('Proposals')}}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link disabled" href="#">{{__('Contracts')}}</a>
                </li>

                </ul>
            </div>

            </li>
        @endcan


        @if(Gate::check('view project') || Gate::check('view task'))
            <li class="nav-item">

            <a class="nav-link {{(Request::segment(1) == 'projects' || Request::segment(1) == 'tasks')?' active':''}}" href="#" data-toggle="collapse" aria-expanded="{{(Request::segment(1) == 'projects' || Request::segment(1) == 'tasks')?'true':'false'}}" data-target="#submenu-3" aria-controls="submenu-3">{{__('Workspace')}}</a>
            <div id="submenu-3" class="{{(Request::segment(1) == 'projects' || Request::segment(1) == 'tasks')?'':'collapse'}}">
                <ul class="nav nav-small flex-column">

                @can('view task')
                <li class="nav-item">
                    <a class="nav-link {{(Request::segment(1) == 'tasks')?' active':''}}" href="{{ route('tasks.board') }}">{{__('Tasks')}}</a>
                </li>
                @endcan

                @can('view project')
                <li class="nav-item">
                    <a class="nav-link {{(Request::segment(1) == 'projects')?' active':''}}" href="{{ route('projects.index') }}">{{__('Projects')}}</a>
                </li>
                @endcan

                </ul>
            </div>

            </li>
        @endif

        @if((Gate::check('view invoice') || Gate::check('view expense') || Gate::check('edit invoice') || Gate::check('view invoice')) || \Auth::user()->type=='client')
            <li class="nav-item">

                <a class="nav-link {{(Request::segment(1) == 'invoices' || Request::segment(1) == 'expenses')?' active':''}}" href="#" data-toggle="collapse" aria-expanded="{{(Request::segment(1) == 'invoices' || Request::segment(1) == 'expenses')?'true':'false'}}" data-target="#submenu-4" aria-controls="submenu-4">{{__('Finances')}}</a>
                <div id="submenu-4" class="{{(Request::segment(1) == 'invoices' || Request::segment(1) == 'expenses')?'':'collapse'}}">
                    <ul class="nav nav-small flex-column">


                    @if(Gate::check('view invoice') || \Auth::user()->type=='client')
                    <li class="nav-item">
                        <a class="nav-link {{(Request::segment(1) == 'invoices')?' active':''}}" href="{{ route('invoices.index') }}">{{__('Invoices')}}</a>
                    </li>
                    @endcan

                    @if(Gate::check('view expense') || \Auth::user()->type=='client')
                    <li class="nav-item">
                        <a class="nav-link {{(Request::segment(1) == 'expenses')?' active':''}}" href="{{ route('expenses.index') }}">{{__('Expenses')}}</a>
                    </li>
                    @endif

                    </ul>
                </div>

            </li>
        @endif

        @if(Gate::check('view project') || \Auth::user()->type!='super admin')
            <li class="nav-item">

                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-5" aria-controls="submenu-5">{{__('Reports')}}</a>
                <div id="submenu-5" class="collapse">
                    <ul class="nav nav-small flex-column">

                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">{{__('Timesheets')}}</a>
                    </li>
                    </ul>
                </div>

            </li>
            {{-- <li class="nav-item">

                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-6" aria-controls="submenu-6">{{__('Files')}}</a>
                <div id="submenu-6" class="collapse">
                    <ul class="nav nav-small flex-column">

                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">{{__('Wiki')}}</a>
                        <a class="nav-link disabled" href="{{ route('sharepoint') }}">{{__('Sharepoint')}}</a>
                    </li>
                    </ul>
                </div>

            </li> --}}
        @endif

    </ul>
    <hr>
    {{-- <div class="d-none d-lg-none d-xl-block w-100">
        <span class="text-small text-muted">{{__('Quick Links')}}</span>
        <ul class="nav nav-small flex-column mt-2">
        @can('view contact')
            <li class="nav-item">
                <a href="{{ route('clients.index') }}" class="nav-link">{{__('Clients')}}</a>
            </li>
        @endcan
        @can('view project')
            <li class="nav-item">
                <a href="{{ route('projects.index') }}" class="nav-link">{{__('Projects')}}</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tasks.board') }}" class="nav-link">{{__('Tasks')}}</a>
            </li>
        @endcan
        </ul>
        <hr>
    </div> --}}
    <div>
        @if(Gate::check('create contact') ||
            Gate::check('create project') ||
            Gate::check('create tasks'))
        <div class="dropdown">
            <button class="btn btn-primary btn-block dropdown-toggle" type="button" id="newContentButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{__('Add New')}}
            </button>
            <div class="dropdown-menu">
                @can('create event')
                    <a class="dropdown-item" href="{{ route('events.create') }}" data-remote="true" data-type="text">{{__('Event')}}</a>
                @endcan
                @can('create task')
                    <a class="dropdown-item" href="{{ route('tasks.create') }}" data-remote="true" data-type="text">{{__('Task')}}</a>
                @endcan
                @can('create contact')
                    <a class="dropdown-item" href="{{ route('contacts.create') }}" data-remote="true" data-type="text">{{__('Contact')}}</a>
                @endcan
                @can('create lead')
                    <a class="dropdown-item" href="{{ route('leads.create') }}" data-remote="true" data-type="text">{{__('Lead')}}</a>
                @endcan    
            </div>
        </div>
        @endif

        @if(\Auth::user()->type !='super admin')
        <form class="form-group my-lg-0 my-2" method="post" autocomplete="off">
            @csrf
            <div class="input-group input-group-dark input-group-round mt-2">
                <input type="search" class="form-control form-control-dark border-0" placeholder="Search" aria-label="Search app" id="search-element">
            </div>
        </form>
        @endif

    </div>
    </div>
    <div class="d-none d-lg-block {{! Cookie::get('laravel_cookie_consent')?'pb-5':''}}">
    <div class="dropup">
        <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {!!Helpers::buildUserAvatar($user, 36, 'round')!!}
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('profile.show')}}">
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
