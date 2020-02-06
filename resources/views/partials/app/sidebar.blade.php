@php
use App\Http\Helpers;

$user=\Auth::user();
$logo = asset(Storage::url('logo/'));

$currantLang = $user->currentLanguage();
$languages=$user->languages();

@endphp

<div class="navbar navbar-expand-lg bg-dark navbar-dark sticky-top" style="overflow:visible">
    <div class="w-100 d-none d-lg-block">
        <a class="navbar-brand float-left" href="{{ route('home') }}">
            <img alt="BaseCRM" width=30 src="{{ asset('assets/img/logo.svg') }}" />
        </a>        
        @include('partials.app.notifications')
    </div>
    <div class="d-lg-none">
        <a class="navbar-brand float-left" href="{{ route('home') }}">
            <img alt="BaseCRM" width=30 src="{{ asset('assets/img/logo.svg') }}" />
        </a>        
        @include('partials.app.notifications')
    </div>
    <div class="d-flex align-items-center">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-block d-lg-none ml-2">
            <div class="dropdown">
            <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {!!Helpers::buildAvatar($user, 36, 'round')!!}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @can('manage account')
                    <a class="dropdown-item" href="{{route('profile.show')}}">
                        {{__('Account Settings')}}
                    </a>
                    <div class="dropdown-divider"></div>
                @endcan

                @if(\Auth::user()->type!='client' && (Gate::check('manage user') || Gate::check('manage role')))
                    @if(Gate::check('manage user'))
                        <a class="dropdown-item" href="{{ route('users.index') }}">{{__('Users')}}</a>
                    @endif
                    @if(Gate::check('manage role'))
                        <a class="dropdown-item" href="{{ route('users.index') }}/#roles">{{__('User Roles')}}</a>
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

        <li class="nav-item active">
            <a class="nav-link" href="{{ route('home') }}">{{__('Dashboard')}}</a>
        </li>

        @if(\Auth::user()->type!='super admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('calendar.index') }}">{{__('Calendar')}}</a>
        </li>
        @endif

        @if(\Auth::user()->type=='super admin')
            @can('create language')
            <li class="nav-item">
                {{-- <a class="nav-link" href="{{route('languages.index',[$currantLang])}}">{{__('Languages')}}</a> --}}
                <a class="nav-link" href="{{url('/languages')}}">{{__('Languages')}}</a>
            </li>
            @endcan

            @can('manage plan')
            <li class="nav-item">
                <a class="nav-link" href="{{route('plans.index')}}">{{__('Price Plans')}}</a>
            </li>
            @endcan

            @can('manage order')
            <li class="nav-item">
                <a class="nav-link" href="{{route('order.index')}}">{{__('Orders')}}</a>
            </li>
            @endcan
        @endif

        @can('manage client')
            <li class="nav-item">

            <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2">{{__('Clients')}}</a>
            <div id="submenu-2" class="collapse">
                <ul class="nav nav-small flex-column">

                <li class="nav-item ">
                    <a class="dropdown-item" href="{{ route('clients.index') }}">{{__('Clients')}}</a>
                </li>

                <li class="nav-item">
                    <a class="dropdown-item" href="{{ route('clients.index') }}/#contacts">{{__('Contacts')}}</a>
                </li>

                @if(Gate::check('manage lead'))
                <li class="nav-item">
                    <a class="dropdown-item" href="{{ route('clients.index') }}/#leads">{{__('Leads')}}</a>
                </li>
                <li class="nav-item">
                    <a class="dropdown-item" href="{{ route('leads.board') }}">{{__('Lead Board')}}</a>
                </li>
                @endif

                </ul>
            </div>

            </li>
        @endcan

        @can('manage project')
            <li class="nav-item">

            <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3">{{__('Projects')}}</a>
            <div id="submenu-3" class="collapse">
                <ul class="nav nav-small flex-column">

                <li class="nav-item">
                    <a class="dropdown-item" href="{{ route('projects.index') }}">{{__('Projects')}}</a>                    
                </li>

                <li class="nav-item">
                    <a class="dropdown-item" href="{{ route('projects.index') }}/#tasks">{{__('Tasks')}}</a>
                </li>

                <li class="nav-item">
                    <a class="dropdown-item" href="{{ route('projects.task.board','*') }}">{{__('Task Board')}}</a>
                </li>

                </ul>
            </div>

            </li>
        @endcan

        @if((Gate::check('manage product') || Gate::check('manage invoice') || Gate::check('manage expense') || Gate::check('manage payment') || Gate::check('manage tax')) || \Auth::user()->type=='client')
            <li class="nav-item">

                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4">{{__('Finances')}}</a>
                <div id="submenu-4" class="collapse">
                    <ul class="nav nav-small flex-column">

                    <li class="nav-item">
                        <a class="dropdown-item disabled" href="#">{{__('Proposals')}}</a>
                    </li>

                    <li class="nav-item">
                        <a class="dropdown-item disabled" href="#">{{__('Contracts')}}</a>
                    </li>

                    @if(Gate::check('manage invoice') || \Auth::user()->type=='client')
                    <li class="nav-item">
                        <a class="dropdown-item" href="{{ route('finances.index') }}/#invoices">{{__('Invoices')}}</a>
                    </li>
                    @endcan

                    @if(Gate::check('manage expense') || \Auth::user()->type=='client')
                    <li class="nav-item">
                        <a class="dropdown-item" href="{{ route('finances.index') }}/#expenses">{{__('Expenses')}}</a>
                    </li>
                    @endif

                    </ul>
                </div>

            </li>
        @endif

        @if(Gate::check('manage project') || \Auth::user()->type!='super admin')
            <li class="nav-item">

                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-5" aria-controls="submenu-5">{{__('Reports')}}</a>
                <div id="submenu-5" class="collapse">
                    <ul class="nav nav-small flex-column">

                    <li class="nav-item">
                        <a class="dropdown-item disabled" href="#">{{__('Timesheets')}}</a>
                    </li>
                    </ul>
                </div>

            </li>
            <li class="nav-item">

                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-6" aria-controls="submenu-6">{{__('Files')}}</a>
                <div id="submenu-6" class="collapse">
                    <ul class="nav nav-small flex-column">

                    <li class="nav-item">
                        <a class="dropdown-item disabled" href="#">{{__('Wiki')}}</a>
                        <a class="dropdown-item disabled" href="{{ route('sharepoint') }}">{{__('Sharepoint')}}</a>
                    </li>
                    </ul>
                </div>

            </li>
        @endif

    </ul>
    <hr>
    <div class="d-none d-lg-block w-100">
        <span class="text-small text-muted">{{__('Quick Links')}}</span>
        <ul class="nav nav-small flex-column mt-2">
        @can('manage contact')
            <li class="nav-item">
                <a href="{{ route('clients.index').'/#contacts' }}" class="nav-link">{{__('Contacts')}}</a>
            </li>
        @endcan
        @can('manage project')
            <li class="nav-item">
                <a href="{{ route('projects.index').'/#projects' }}" class="nav-link">{{__('Projects')}}</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('projects.index').'/#tasks' }}" class="nav-link">{{__('Tasks')}}</a>
            </li>
        @endcan
        </ul>
        <hr>
    </div>
    <div>
        @if(\Auth::user()->type !='super admin')
        <form class="form-inline my-lg-0 my-2" method="post" autocomplete="off">
            @csrf
            <div class="input-group input-group-dark input-group-round">
                <input type="search" class="form-control form-control-dark border-0" placeholder="Search" aria-label="Search app" id="search-element">
            </div>
        </form>
        @endif

        @if(Gate::check('create contact') || 
            Gate::check('create project') ||
            Gate::check('create tasks'))
        <div class="dropdown mt-2">
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
                <a class="dropdown-item" href="{{ route('projects.task.create', '0') }}" data-remote="true" data-type="text">{{__('Task')}}</a>
            @endcan
            </div>
        </div>
        @endif
    </div>
    </div>
    <div class="d-none d-lg-block">
    <div class="dropup">
        <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {!!Helpers::buildAvatar($user, 36, 'round')!!}
        </a>
        <div class="dropdown-menu">
            @can('manage account')
                <a class="dropdown-item" href="{{route('profile.show')}}">
                    {{__('Account Settings')}}
                </a>
                <div class="dropdown-divider"></div>
            @endcan

            @if(\Auth::user()->type!='client' && (Gate::check('manage user') || Gate::check('manage role')))
                @if(Gate::check('manage user'))
                    <a class="dropdown-item" href="{{ route('users.index') }}">{{__('Users')}}</a>
                @endif
                @if(Gate::check('manage role'))
                    <a class="dropdown-item" href="{{ route('users.index') }}/#roles">{{__('User Roles')}}</a>
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