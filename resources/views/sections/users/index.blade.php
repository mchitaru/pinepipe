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
    
        var hash = window.location.hash ? window.location.hash : '#users';
    
        $('.nav-tabs a[href="' + hash + '"]').tab('show');

    });
       
</script>

@endpush

@section('page-title')
    {{__('User')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('User')}}</li>
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
                <a class="nav-link " data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="true">{{__('Users')}}
                    <span class="badge badge-secondary">{{ count($users) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link " data-toggle="tab" href="#roles" role="tab" aria-controls="roles" aria-selected="false">{{__('roles')}}
                    <span class="badge badge-secondary">{{ count($roles) }}</span>
                </a>
            </li>
        </ul>
        <div class="tab-content">
        <div class="tab-pane fade show " id="users" role="tabpanel" data-filter-list="content-list-body">
            <div class="row content-list-head">
            <div class="col-auto">
                <h3>{{__('Users')}}</h3>
                @can('create user')
                    <a href="{{ route('users.create') }}" class="btn btn-round" data-remote="true" data-type="text">
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
                <input type="search" class="form-control filter-list-input" placeholder="Filter users" aria-label="Filter Users">
                </div>
            </form>
            </div>
            <!--end of content list head-->
            <div class="content-list-body">

                @include('users.index');

            </div>
            </div>
            <!--end of modal body-->
            <div class="tab-pane fade show " id="roles" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('User Roles')}}</h3>
                    @can('create user')
                        <a href="{{ route('roles.create') }}" class="btn btn-round" data-remote="true" data-type="text">
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
                    <input type="search" class="form-control filter-list-input" placeholder="Filter users" aria-label="Filter Users">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    @include('roles.index')
                </div>
            </div>    
        </div>
    </div>
</div>
</div>
@endsection
