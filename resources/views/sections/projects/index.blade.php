@extends('layouts.app')

@php
    $profile=asset(Storage::url('avatar/'));
@endphp

@push('stylesheets')
@endpush

@push('scripts')

<script>

// keep active tab
// $(document).ready(function() {

//     $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
//         window.location.hash = $(e.target).attr('href');
//         $(window).scrollTop(0);
//     });

//     var hash = window.location.hash ? window.location.hash : '#projects';
    
//     $('.nav-tabs a[href="' + hash + '"]').tab('show');
    
// });
    
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

            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#team-manage-modal">Edit Team</a>
            <a class="dropdown-item" href="#">Share</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="#">Leave</a>

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
            <a class="nav-link {{(Request::segment(1)=='projects')?'active':''}}" data-toggle="tab" href="#projects" role="tab" aria-controls="projects" aria-selected="true">Projects
                <span class="badge badge-secondary">{{ count($projects) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{(Request::segment(1)=='tasks')?'active':''}}" data-toggle="tab" href="#tasks" role="tab" aria-controls="tasks" aria-selected="false">Tasks</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{(Request::segment(1)=='activity')?'active':''}}" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">{{__('Activity')}}</a>
        </li>
        </ul>
        <div class="tab-content">
        <div class="tab-pane fade show {{(Request::segment(1)=='projects')?'active':''}}" id="projects" role="tabpanel" data-filter-list="content-list-body">
            <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>{{__('Projects')}}</h3>
                    @can('create project')
                    <button class="btn btn-round" data-url="{{ route('projects.create') }}" data-ajax-popup="true" data-title="{{__('Create New Project')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
                        <i class="material-icons">add</i>
                    </button>
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
            <div class="tab-pane fade show {{(Request::segment(1)=='tasks')?'active':''}}" id="tasks" role="tabpanel" data-filter-list="card-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Tasks')}}</h3>
                    <button class="btn btn-round" data-url="{{ route('tasks.create') }}" data-ajax-popup="true" data-title="{{__('Add')}}" data-toggle="tooltip" data-original-title="{{__('Add')}}">
                        <i class="material-icons">add</i>
                    </button>
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

                    @include('tasks.index');
                <!--end of content list body-->
                </div>
                <!--end of content list-->
            </div>
            <!--end of tab-->
            <div class="tab-pane fade {{(Request::segment(1)=='activity')?'active':''}}" id="activity" role="tabpanel" data-filter-list="list-group-activity">
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
</div>
@endsection
