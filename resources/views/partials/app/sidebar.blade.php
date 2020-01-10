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
    <div class="d-block d-lg-none ml-2">
        <div class="dropdown">
        <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img alt="Image" src="assets/img/avatar-male-4.jpg" class="avatar" />
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a href="nav-side-user.html" class="dropdown-item">Profile</a>
            <a href="utility-account-settings.html" class="dropdown-item">Account Settings</a>
            <a href="#" class="dropdown-item">Log Out</a>
        </div>
        </div>
    </div>
    </div>
    <div class="collapse navbar-collapse flex-column" id="navbar-collapse">
    <ul class="navbar-nav d-lg-block">

        <li class="nav-item active">
            <a class="nav-link" href="{{ route('home') }}">{{__('Workspace')}}</a>
        </li>

        @if(\Auth::user()->type!='super admin' && Gate::check('manage client'))
        <li class="nav-item">

        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2">Clients</a>
        <div id="submenu-2" class="collapse">
            <ul class="nav nav-small flex-column">

            <li class="nav-item ">
                <a class="dropdown-item" href="{{ route('clients.index') }}/#clients">{{__('Clients')}}</a>
            </li>

            <li class="nav-item">
                <a class="dropdown-item" href="{{ route('clients.index') }}/#contacts">{{__('Contacts')}}</a>
            </li>

            @if(Gate::check('manage lead'))
            <li class="nav-item">
                    <a class="dropdown-item" href="#">{{__('Leads')}}</a>
            </li>
            @endif

            </ul>
        </div>

        </li>
        @endif

        @if(Gate::check('manage project'))

        <li class="nav-item">

        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3">Projects</a>
        <div id="submenu-3" class="collapse">
            <ul class="nav nav-small flex-column">

            <li class="nav-item">
                <a class="dropdown-item" href="{{ route('projects.index') }}">{{__('Projects')}}</a>
            </li>

            <li class="nav-item">
                <a class="dropdown-item" href="{{ route('projects.index') }}">{{__('Tasks')}}</a>
            </li>

            <li class="nav-item">
                <a class="dropdown-item disabled" href="#">{{__('Kanban')}}</a>
            </li>

            </ul>
        </div>

        </li>
        @endif

        @if((Gate::check('manage product') || Gate::check('manage invoice') || Gate::check('manage expense') || Gate::check('manage payment') || Gate::check('manage tax')) || \Auth::user()->type=='client')
        <li class="nav-item">

            <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4">Finance</a>
            <div id="submenu-4" class="collapse">
                <ul class="nav nav-small flex-column">

                <li class="nav-item">
                    <a class="dropdown-item disabled" href="#">{{__('Proposals')}}</a>
                </li>

                <li class="nav-item">
                    <a class="dropdown-item disabled" href="#">{{__('Contracts')}}</a>
                </li>

                <li class="nav-item">
                    <a class="dropdown-item disabled" href="#">{{__('Invoices')}}</a>
                </li>

                <li class="nav-item">
                    <a class="dropdown-item disabled" href="#">{{__('Expenses')}}</a>
                </li>

                </ul>
            </div>

        </li>
        @endif

        @if(Gate::check('manage project'))
        <li class="nav-item">

            <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4">Reports</a>
            <div id="submenu-4" class="collapse">
                <ul class="nav nav-small flex-column">

                <li class="nav-item">
                    <a class="dropdown-item disabled" href="#">{{__('Timesheets')}}</a>
                </li>
                </ul>
            </div>

        </li>
        @endif

    </ul>
    <hr>
    <div class="d-none d-lg-block w-100">
        <span class="text-small text-muted">Quick Links</span>
        <ul class="nav nav-small flex-column mt-2">
        <li class="nav-item">
            <a href="nav-side-team.html" class="nav-link">Team Overview</a>
        </li>
        <li class="nav-item">
            <a href="nav-side-project.html" class="nav-link">Project</a>
        </li>
        <li class="nav-item">
            <a href="nav-side-task.html" class="nav-link">Single Task</a>
        </li>
        <li class="nav-item">
            <a href="nav-side-kanban-board.html" class="nav-link">Kanban Board</a>
        </li>
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

        @if(Gate::check('manage project'))
        <div class="dropdown mt-2">
            <button class="btn btn-primary btn-block dropdown-toggle" type="button" id="newContentButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Add New
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item disabled" href="#">{{__('Contact')}}</a>
                <a class="dropdown-item disabled" href="#">{{__('Project')}}</a>
                <a class="dropdown-item disabled" href="#">{{__('Task')}}</a>
            </div>
        </div>
        @endif
    </div>
    </div>
    <div class="d-none d-lg-block">
    <div class="dropup">
        <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img alt="{{$users->name}}" title="{{$users->name}}" src="{{(!empty($users->avatar)? $profile.'/'.$users->avatar : $profile.'/avatar.png')}}" class="avatar" />
        </a>
        <div class="dropdown-menu">
            @can('manage account')
            <a href="{{route('profile',$users->id)}}" class="dropdown-item">
                {{__('Profile')}}
            </a>
            @endcan
            <a href="utility-account-settings.html" class="dropdown-item">Account Settings</a>
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
