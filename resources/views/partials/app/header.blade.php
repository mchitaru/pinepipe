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
        <a class="nav-link" href="{{ route('home') }}">{{__('Workspace')}}</a>
    </li>

    @if(\Auth::user()->type!='super admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('calendar.index') }}">{{__('Calendar')}}</a>
    </li>
    @endif

    @if(\Auth::user()->type=='super admin')
        @can('create language')
        <li class="nav-item">
            <a class="nav-link" href="{{route('manage.language',[$currantLang])}}">{{__('Languages')}}</a>
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

    @if(\Auth::user()->type!='super admin' && Gate::check('manage client'))
    <li class="nav-item">

        <div class="dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="nav-dropdown-2">{{__('Clients')}}</a>
            <div class="dropdown-menu">

                <a class="dropdown-item" href="{{ route('clients.index') }}/#clients">{{__('Clients')}}</a>

                <a class="dropdown-item" href="{{ route('contacts.index') }}/#contacts">{{__('Contacts')}}</a>

                @if(Gate::check('manage lead'))
                    <a class="dropdown-item disabled" href="#">{{__('Leads')}}</a>
                @endif
            </div>
        </div>
    </li>
    @endif

    @if(Gate::check('manage project'))
    <li class="nav-item">

        <div class="dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="nav-dropdown-2">{{__('Projects')}}</a>
        <div class="dropdown-menu">

            <a class="dropdown-item" href="{{ route('projects.index') }}">{{__('Projects')}}</a>

            <a class="dropdown-item" href="{{ route('projects.index') }}">{{__('Tasks')}}</a>

            <a class="dropdown-item disabled" href="#">{{__('Kanban')}}</a>

        </div>
        </div>

    </li>
    @endif

    @if((Gate::check('manage product') || Gate::check('manage invoice') || Gate::check('manage expense') || Gate::check('manage payment') || Gate::check('manage tax')) || \Auth::user()->type=='client')
    <li class="nav-item">

        <div class="dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true" id="nav-dropdown-2">{{__('Finance')}}</a>
        <div class="dropdown-menu">

            <a class="dropdown-item disabled" href="#">{{__('Proposals')}}</a>

            <a class="dropdown-item disabled" href="#">{{__('Contracts')}}</a>

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

    @if(Gate::check('manage project'))
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

        @if(Gate::check('manage project'))
        <div class="dropdown mx-lg-2">
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

        <!-- Profile menu --->
        <div class="dropdown mx-lg-2 float-right">
            <div class="dropdown dropdown-toggle">
            <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img alt="{{$users->name}}" {!! empty($users->avatar) ? "avatar='".$users->name."'" : "" !!} class="avatar" src="{{asset(Storage::url("avatar/".$users->avatar))}}" data-filter-by="alt"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                @can('manage account')
                <a href="{{route('profile',$users->id)}}" class="dropdown-item">
                    {{__('Profile')}}
                </a>
                @endcan

                <div class="dropdown-divider"></div>

                @if(\Auth::user()->type!='client')
                @if(\Auth::user()->type=='super admin' || Gate::check('manage user'))
                    @can('manage user')
                    <a class="dropdown-item" href="{{ route('users.index') }}">{{__('Users')}}</a>
                    @endcan
                @endif
                @if(Gate::check('manage role'))
                    <a class="dropdown-item" href="{{ route('roles.index') }}">{{__('User Roles')}}</a>
                @endif
                @endif

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
