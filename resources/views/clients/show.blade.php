@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')

<script>

$(function() {

    $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
        window.history.replaceState(null, null, $(e.target).attr('href'));
        window.location.hash = $(e.target).attr('href');

        var id = $(e.target).attr("href");
        sessionStorage.setItem('clients.tab', id);
    });

    var hash = window.location.hash ? window.location.hash : sessionStorage.getItem('clients.tab');

    if(hash == null) hash = '#profile';

    $('a[data-toggle="tab"][href="' + hash + '"]').tab('show');

});
</script>

@endpush

@section('page-title')
    {{$client->name}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ route('clients.index') }}">{{__('Clients')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{$client->name}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item" href="#">{{__('New Client')}}</a>
            <a class="dropdown-item" href="#">{{__('New contact')}}</a>

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
                <a class="nav-link" data-toggle="tab" href="#profile" role="tab" aria-controls="contacts" aria-selected="false">{{__('Profile')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#contacts" role="tab" aria-controls="contacts" aria-selected="false">{{__('Contacts')}}
                    <span class="badge badge-secondary">{{ $contacts->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#leads" role="tab" aria-controls="leads" aria-selected="false">{{__('Leads')}}
                    <span class="badge badge-secondary">{{ $leads->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#projects" role="tab" aria-controls="projects" aria-selected="true">{{__('Projects')}}
                    <span class="badge badge-secondary">{{ $projects->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">{{__('Activity')}}</a>
            </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show" id="profile" role="tabpanel">
                    <div class="row content-list-head">
                        <div class="col-auto">
                            <h3>{{__('Profile')}}</h3>
                        </div>
                    </div>
                    <!--end of content list head-->
                    <div class="content-list-body">@include('clients.profile')</div>
                    <!--end of content list body-->
                </div>
                <!--end of tab-->
                <div class="tab-pane fade show" id="contacts" role="tabpanel" data-filter-list="content-list-body">
                    <div class="row content-list-head">
                        <div class="col-auto">
                            <h3>{{__('Contacts')}}</h3>
                            @can('create contact')
                            <a href="{{ route('contacts.create') }}" class="btn btn-round" data-params="client_id={{$client->id}}" data-remote="true" data-type="text">
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
                            <input type="search" class="form-control filter-list-input" placeholder="{{__('Filter Contacts')}}" aria-label="{{__('Filter Contacts')}}">
                            </div>
                        </form>
                    </div>
                    <!--end of content list head-->
                    <div class="content-list-body">@include('contacts.index')</div>
                    <!--end of content list body-->
                </div>
                <!--end of tab-->
                <div class="tab-pane fade show {{(Request::segment(1)=='leads')?'active':''}}" id="leads" role="tabpanel" data-filter-list="content-list-body">
                    <div class="row content-list-head">
                        <div class="col-auto">
                            <h3>{{__('Leads')}}</h3>
                            @can('create lead')
                            <a href="{{ route('leads.create') }}" class="btn btn-round" data-params="client_id={{$client->id}}" data-remote="true" data-type="text">
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
                            <input type="search" class="form-control filter-list-input" placeholder="{{__('Filter Leads')}}" aria-label="{{__('Filter Leads')}}">
                            </div>
                        </form>
                        </div>
                        <!--end of content list head-->
                        <div class="content-list-body">@include ('leads.index')</div>
                    <!--end of content list body-->
                </div>
                <!--end of tab-->
                <div class="tab-pane fade show" id="projects" role="tabpanel" data-filter-list="content-list-body">
                    <div class="content-list">
                        <div class="row content-list-head">
                            <div class="col-auto">
                            <h3>{{__('Projects')}}</h3>
                            @can('create project')
                            <a href="{{ route('projects.create') }}" class="btn btn-round" data-params="client_id={{$client->id}}" data-remote="true" data-type="text">
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
                        <div class="content-list-body row">@include('projects.index')</div>
                    <!--end of content list body-->
                    </div>
                    <!--end of content list-->
                </div>
                <!--end of tab-->
                <div class="tab-pane fade" id="activity" role="tabpanel" data-filter-list="list-group-activity">
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
                    <div class="content-list-body">@include('activity.index')</div>
                    </div>
                    <!--end of content list-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
