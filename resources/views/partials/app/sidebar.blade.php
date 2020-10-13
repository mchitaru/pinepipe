@php
use Carbon\Carbon;

$logo = asset(Storage::url('logo/'));

$languages = $_user->languages();

@endphp

<div class="navbar navbar-expand-lg bg-light navbar-light sticky-top" style="overflow:visible;">
    <div class="w-100 d-none d-lg-block">
        <div class="navbar-brand float-left p-0">
            <a class="navbar-brand float-left p-0" href="{{ route('home') }}">
                <img alt="Pinepipe" height=40 src="{{ asset('assets/img/logo-dark-full.png') }}" />
            </a>
        </div>
        {{-- <div class="dropdown float-right">
            @include('partials.app.notifications')
        </div> --}}
    </div>
    <div class="d-block d-lg-none">
        <a class="navbar-brand float-left p-0" href="{{ route('home') }}">
            <img alt="Pinepipe" width=32 src="{{ asset('assets/img/logo-dark.png') }}" />
        </a>
        <div class="dropdown">
            {{-- <div class="dropdown float-right pl-1">
                @include('partials.app.notifications')
            </div> --}}
        </div>
    </div>
    <div class="d-block d-lg-none">
        @if(\Auth::user()->type!='super admin')
        <div class="align-items-center">
            @include('partials.app.timesheets')
        </div>
        @endif
    </div>
    <div class="d-lg-none d-flex align-items-center">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="dropdown ml-2">
            <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{$_user->name}}">
                {!!Helpers::buildUserAvatar($_user, 32, 'rounded')!!}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{route('profile.edit')}}">
                    {{__('My Profile')}}
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                    {{__('Logout')}}
                </a>
            </div>
        </div>
    </div>
    <div class="collapse navbar-collapse flex-column" id="navbar-collapse">
    <ul class="navbar-nav d-lg-block">
        <li class="nav-item">
            <a class="nav-link d-flex {{(empty(Request::segment(1)) || Request::segment(1) == 'home')?' active':''}}" href="{{ route('home') }}">
                <i class="material-icons pr-2">home</i>
                {{__('Home')}}
            </a>
        </li>
        @if(\Auth::user()->type!='super admin')
        <li class="nav-item">
            <a class="nav-link d-flex {{(Request::segment(1) == 'calendar')?' active':''}}" href="{{ route('calendar.index') }}">
                <i class="material-icons pr-2">calendar_today</i>
                {{__('Calendar')}}
            </a>
        </li>
        @endif
        @if(\Auth::user()->type=='super admin')
            <li class="nav-item">
                <a class="nav-link" href="{{url('/languages')}}">
                    <i class="material-icons pr-2">language</i>
                    {{__('Languages')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('plans.index')}}">
                    <i class="material-icons pr-2">money</i>
                    {{__('Subscription Plans')}}
                </a>
            </li>
        @endif
        @can('viewAny', 'App\Client')
        <li class="nav-item ">
            <a class="nav-link d-flex {{(Request::segment(1) == 'clients')?' active':''}}" href="{{ route('clients.index') }}">
                <i class="material-icons pr-2">business</i>
                {{__('Clients')}}
            </a>
        </li>
        @endcan
        @if(Gate::check('viewAny', 'App\Lead') || Gate::check('viewAny', 'App\Contact'))
            <li class="nav-item">

            <a class="nav-link {{(Request::segment(1) == 'contacts' || Request::segment(1) == 'leads')?' active':''}}" href="#" data-toggle="collapse" aria-expanded="{{(Request::segment(1) == 'contacts' || Request::segment(1) == 'leads')?'true':'false'}}" data-target="#submenu-2" aria-controls="submenu-2">
                <div class="d-flex ">
                    <i class="material-icons pr-2">call</i>
                    {{__('Sales')}}
                </div>
            </a>
            <div id="submenu-2" class="{{(Request::segment(1) == 'contacts' || Request::segment(1) == 'leads')?'':'collapse'}}">
                <ul class="nav nav-small flex-column">

                @can('viewAny', 'App\Lead')
                <li class="nav-item pl-3">
                    <a class="nav-link {{(Request::segment(1) == 'leads')?' active':''}}" href="{{ route('leads.board') }}">{{__('Leads')}}</a>
                </li>
                @endif

                @can('viewAny', 'App\Contact')
                <li class="nav-item pl-3">
                    <a class="nav-link {{(Request::segment(1) == 'contacts')?' active':''}}" href="{{ route('contacts.index') }}">{{__('Contacts')}}</a>
                </li>
                @endif

                <li class="nav-item pl-3">
                    <a class="nav-link disabled" href="#">{{__('Proposals')}}</a>
                </li>

                <li class="nav-item pl-3">
                    <a class="nav-link disabled" href="#">{{__('Contracts')}}</a>
                </li>

                </ul>
            </div>

            </li>
        @endcan
        @if(Gate::check('viewAny', 'App\Project') || Gate::check('viewAny', 'App\Task'))
            <li class="nav-item">

            <a class="nav-link {{(Request::segment(1) == 'projects' || Request::segment(1) == 'tasks')?' active':''}}" href="#" data-toggle="collapse" aria-expanded="{{(Request::segment(1) == 'projects' || Request::segment(1) == 'tasks')?'true':'false'}}" data-target="#submenu-3" aria-controls="submenu-3">
                <div class="d-flex ">
                    <i class="material-icons pr-2">work_outline</i>
                    {{__('Workspace')}}
                </div>
            </a>
            <div id="submenu-3" class="{{(Request::segment(1) == 'projects' || Request::segment(1) == 'tasks')?'':'collapse'}}">
                <ul class="nav nav-small flex-column">

                @can('viewAny', 'App\Task')
                <li class="nav-item pl-3">
                    <a class="nav-link {{(Request::segment(1) == 'tasks')?' active':''}}" href="{{ route('tasks.board') }}">{{__('Tasks')}}</a>
                </li>
                @endcan

                @can('viewAny', 'App\Project')
                <li class="nav-item pl-3">
                    <a class="nav-link {{(Request::segment(1) == 'projects')?' active':''}}" href="{{ route('projects.index') }}">{{__('Projects')}}</a>
                </li>
                @endcan

                </ul>
            </div>

            </li>
        @endif
        @if((Gate::check('viewAny', 'App\Invoice') || Gate::check('viewAny', 'App\Expense') || Gate::check('viewAny', 'App\Invoice') || Gate::check('viewAny', 'App\Invoice')) || \Auth::user()->type=='client')
            <li class="nav-item">

                <a class="nav-link {{(Request::segment(1) == 'invoices' || Request::segment(1) == 'expenses')?' active':''}}" href="#" data-toggle="collapse" aria-expanded="{{(Request::segment(1) == 'invoices' || Request::segment(1) == 'expenses')?'true':'false'}}" data-target="#submenu-4" aria-controls="submenu-4">
                    <div class="d-flex ">
                        <i class="material-icons pr-2">account_balance</i>
                        {{__('Finances')}}
                    </div>
                </a>
                <div id="submenu-4" class="{{(Request::segment(1) == 'invoices' || Request::segment(1) == 'expenses')?'':'collapse'}}">
                    <ul class="nav nav-small flex-column">


                    @if(Gate::check('viewAny', 'App\Invoice') || \Auth::user()->type=='client')
                    <li class="nav-item pl-3">
                        <a class="nav-link {{(Request::segment(1) == 'invoices')?' active':''}}" href="{{ route('invoices.index') }}">{{__('Invoices')}}</a>
                    </li>
                    @endcan

                    @if(Gate::check('viewAny', 'App\Expense') || \Auth::user()->type=='client')
                    <li class="nav-item pl-3">
                        <a class="nav-link {{(Request::segment(1) == 'expenses')?' active':''}}" href="{{ route('expenses.index') }}">{{__('Expenses')}}</a>
                    </li>
                    @endif

                    </ul>
                </div>

            </li>
        @endif
        @if(Gate::check('viewAny', 'App\Project') || \Auth::user()->type!='super admin')
            <li class="nav-item">

                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-5" aria-controls="submenu-5">
                    <div class="d-flex ">
                        <i class="material-icons pr-2">show_chart</i>
                        {{__('Reports')}}
                    </div>
                </a>
                <div id="submenu-5" class="collapse">
                    <ul class="nav nav-small flex-column">

                    <li class="nav-item pl-3">
                        <a class="nav-link" href="{{route('timesheets.index')}}">{{__('Timesheets')}}</a>
                    </li>
                    </ul>
                </div>

            </li>
        @endif
    </ul>
    <hr>
    <div class="w-100">
        <div class="d-block d-lg-none pb-2">
            @if(\Auth::user()->type !='super admin')
            <div class="input-group input-group-light ">
                <input type="search" class="form-control form-control-light search-element" placeholder="{{__("Search...")}}" aria-label="Search app">
            </div>
            @endif
        </div>
        @if(Gate::check('create', 'App\Contact') ||
            Gate::check('create', 'App\Project') ||
            Gate::check('create tasks'))
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle col" type="button" id="newContentButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{__('Add New')}}
            </button>
            <div class="dropdown-menu">
                @can('create', 'App\Event')
                    <a class="dropdown-item" href="{{ route('events.create') }}" data-remote="true" data-type="text">{{__('Event')}}</a>
                @endcan
                @can('create', 'App\Task')
                    <a class="dropdown-item" href="{{ route('tasks.create') }}" data-remote="true" data-type="text">{{__('Task')}}</a>
                @endcan
                @can('create', 'App\Contact')
                    <a class="dropdown-item" href="{{ route('contacts.create') }}" data-remote="true" data-type="text">{{__('Contact')}}</a>
                @endcan
                @can('create', 'App\Lead')
                    <a class="dropdown-item" href="{{ route('leads.create') }}" data-remote="true" data-type="text">{{__('Lead')}}</a>
                @endcan
                @can('create', 'App\Invoice')
                    <a class="dropdown-item" href="{{ route('invoices.create') }}" data-remote="true" data-type="text">{{__('Invoice')}}</a>
                @endcan
            </div>
        </div>
        @endif
    </div>
    </div>
    @if($_user->type == 'company' && !$_user->subscribed())
    <hr>
    <div class="d-none d-lg-block w-100">
        <a href="{{route('subscription')}}" class="nav-link d-flex p-0">
            <i class="material-icons pr-2">card_membership</i>
            {{__('Activate subscription')}}
        </a>
    </div>
    @endif
    <div class="d-none d-lg-block {{! Cookie::get('laravel_cookie_consent')?'pb-5':''}}">
    </div>
</div>
