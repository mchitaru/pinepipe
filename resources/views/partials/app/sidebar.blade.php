@php
    $users=\Auth::user();
    $profile=asset(Storage::url('avatar/'));
    $logo=asset(Storage::url('logo/'));

    $currantLang = $users->currentLanguage();
    $languages=$users->languages();

@endphp

<div class="navbar navbar-expand-lg bg-dark navbar-dark sticky-top">
        <a class="navbar-brand" href="index.html">
            <img alt="Pipeline" width=200 src="{{ asset('assets/img/logo.png') }}" />
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

            <a class="nav-link" href="{{ route('home') }}">Workspace</a>

        </li>

        <li class="nav-item">

        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2">Clients</a>
        <div id="submenu-2" class="collapse">
            <ul class="nav nav-small flex-column">

            <li class="nav-item ">
                <a class="dropdown-item" href="{{ route('clients.index') }}">Clients</a>
            </li>

            <li class="nav-item">
                <a class="dropdown-item" href="{{ route('clients.index') }}">Contacts</a>
            </li>

            <li class="nav-item">
                <a class="dropdown-item" href="{{ route('kanban') }}">Leads</a>
            </li>

            </ul>
        </div>

        </li>

        <li class="nav-item">

        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3">Projects</a>
        <div id="submenu-3" class="collapse">
            <ul class="nav nav-small flex-column">

            <li class="nav-item">
                <a class="dropdown-item" href="{{ route('team') }}">Team</a>
            </li>

            <li class="nav-item">
                <a class="dropdown-item" href="{{ route('project') }}">Project</a>
            </li>

            <li class="nav-item">
                <a class="dropdown-item" href="{{ route('task') }}">Task</a>
            </li>

            <li class="nav-item">
                <a class="dropdown-item" href="{{ route('kanban') }}">Kanban</a>
            </li>

            </ul>
        </div>

        </li>

        <li class="nav-item">

            <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4">Finance</a>
            <div id="submenu-4" class="collapse">
                <ul class="nav nav-small flex-column">

                <li class="nav-item">
                    <a class="dropdown-item" href="{{ route('team') }}">Proposals</a>
                </li>

                <li class="nav-item">
                    <a class="dropdown-item" href="{{ route('project') }}">Contracts</a>
                </li>

                <li class="nav-item">
                    <a class="dropdown-item" href="{{ route('task') }}">Invoices</a>
                </li>

                <li class="nav-item">
                    <a class="dropdown-item" href="{{ route('kanban') }}">Expenses</a>
                </li>

                </ul>
            </div>

        </li>

        <li class="nav-item">

        <a class="nav-link" href="documentation/changelog.html">Timesheets</a>

        </li>

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
        <form>
        <div class="input-group input-group-dark input-group-round">
            <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="material-icons">search</i>
            </span>
            </div>
            <input type="search" class="form-control form-control-dark" placeholder="Search" aria-label="Search app">
        </div>
        </form>
        <div class="dropdown mt-2">
            <button class="btn btn-primary btn-block dropdown-toggle" type="button" id="newContentButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Add New
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="#">Team</a>
              <a class="dropdown-item" href="#">Project</a>
              <a class="dropdown-item" href="#">Task</a>
            </div>
        </div>
  </div>
    </div>
    <div class="d-none d-lg-block">
    <div class="dropup">
        <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img alt="Image" src="assets/img/avatar-male-4.jpg" class="avatar" />
        </a>
        <div class="dropdown-menu">
        <a href="nav-side-user.html" class="dropdown-item">Profile</a>
        <a href="utility-account-settings.html" class="dropdown-item">Account Settings</a>
        <a href="#" class="dropdown-item">Log Out</a>
        </div>
    </div>
    </div>

</div>
