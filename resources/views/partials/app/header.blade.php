<div class="navbar navbar-expand-lg bg-dark navbar-dark sticky-top">
<a class="navbar-brand" href="{{ route('home') }}">
    <img alt="Pipeline" width=30 src="assets/img/logo.svg" />
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

            <a class="dropdown-item" href="{{ route('team') }}">Team</a>

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
    <form class="form-inline my-lg-0 my-2">
        <div class="input-group input-group-dark input-group-round">
        <div class="input-group-prepend">
            <span class="input-group-text">
            <i class="material-icons">search</i>
            </span>
        </div>
        <input type="search" class="form-control form-control-dark" placeholder="Search" aria-label="Search app">
        </div>
    </form>
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
    <div class="dropdown mx-lg-2">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">settings</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item" href="{{ route('users.index') }}">Users</a>
            <a class="dropdown-item" href="#">Share</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="#">Leave</a>
        </div>
    </div>
    <div class="d-none d-lg-block">
        <div class="dropdown">
        <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img alt="Image" src="assets/img/avatar-male-4.jpg" class="avatar" />
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ route('user') }}" class="dropdown-item">Profile</a>
            <a href="utility-account-settings.html" class="dropdown-item">Account Settings</a>
            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">
                <i class="icon-key"></i> {{__('Logout')}}
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
