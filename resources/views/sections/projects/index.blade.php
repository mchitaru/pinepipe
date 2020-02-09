@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')
<script>

    // keep active tab
    $(document).ready(function() {

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) 
        {
            window.history.replaceState(null, null, $(e.target).attr('href'));
            window.location.hash = $(e.target).attr('href');
            $(window).scrollTop(0);
        });
    
        var hash = window.location.hash ? window.location.hash : '#projects';
    
        $('.nav-tabs a[href="' + hash + '"]').tab('show');

    });
       
</script>

@endpush

@section('page-title')
    {{__('Projects')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Projects')}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            @can('create project')
                <a class="dropdown-item" href="{{ route('projects.create') }}" data-remote="true" data-type="text">{{__('New Project')}}</a>
            @endcan
            @can('create task')
                <a class="dropdown-item" href="{{ route('projects.task.create', '0') }}" data-remote="true" data-type="text">{{__('New Task')}}</a>
            @endcan
            
            <div class="dropdown-divider"></div>
            
            @can('manage task')
                <a class="dropdown-item" href="{{route('projects.task.board', '*')}}">{{__('Task Board')}}</a>
            @endcan

            <div class="dropdown-divider"></div>
            <a class="dropdown-item disabled" href="#" data-remote="true" data-type="text">{{__('Import')}}</a>
            <a class="dropdown-item disabled" href="#" data-remote="true" data-type="text">{{__('Export')}}</a>

        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="page-header">
        </div>
        <ul class="nav nav-tabs nav-fill" role="tablist">
        <li class="nav-item">
            <a class="nav-link " data-toggle="tab" href="#projects" role="tab" aria-controls="projects" aria-selected="true">Projects
                <span class="badge badge-secondary">{{ count($projects) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " data-toggle="tab" href="#tasks" role="tab" aria-controls="tasks" aria-selected="false">Tasks
                <span class="badge badge-secondary">{{ $task_count }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">{{__('Activity')}}</a>
        </li>
        </ul>
        <div class="tab-content">
        <div class="tab-pane fade show " id="projects" role="tabpanel" data-filter-list="content-list-body">
            <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>{{__('Projects')}}</h3>
                    @can('create project')

                    <a href="{{ route('projects.create') }}" class="btn btn-round" data-remote="true" data-type="text">
                        <i class="material-icons">add</i>
                    </a>

                    @endcan
                    </div>
                    <form class="col-md-auto">
                    <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                        </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="Filter projects" aria-label="Filter Projects">
                    </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body row">
                    @include('projects.index')
				</div>
            <!--end of content list body-->
            </div>
            <!--end of content list-->
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show " id="tasks" role="tabpanel" data-filter-list="card-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Tasks')}}</h3>

                    <a href="{{ route('projects.task.create', '0') }}" class="btn btn-round" data-remote="true" data-type="text">
                        <i class="material-icons">add</i>
                    </a>

                </div>
                <form class="col-md-auto">
                    <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="material-icons">filter_list</i>
                        </span>
                    </div>
                    <input type="search" class="form-control filter-list-input" placeholder="Filter tasks" aria-label="Filter Tasks">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">

                    @include('tasks.index')
                <!--end of content list body-->
                </div>
                <!--end of content list-->
            </div>
            <!--end of tab-->
            <div class="tab-pane fade " id="activity" role="tabpanel" data-filter-list="list-group-activity">
                <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>Activity</h3>
                    </div>
                    <form class="col-md-auto">
                    <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                        </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="Filter activity" aria-label="Filter activity">
                    </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    @include('activity.index')
                </div>
                </div>
                <!--end of content list-->
            </div>
            </div>
            <!--end of tab-->
        </div>
    </div>
</div>
@endsection
