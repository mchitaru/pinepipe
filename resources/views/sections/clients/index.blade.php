@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')

<script>

// keep active tab
$(document).ready(function() {

    // $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    //     window.location.hash = $(e.target).attr('href');
    //     $(window).scrollTop(0);
    // });

    // var hash = window.location.hash ? window.location.hash : '#clients';

    // $('.nav-tabs a[href="' + hash + '"]').tab('show');

});

</script>

@endpush

@section('page-title')
    {{__('Clients')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Clients')}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            @can('create client')
                <a class="dropdown-item" href="#" data-url="{{ route('clients.create') }}" data-ajax-popup="true" data-title="{{__('Create New Client')}}">{{__('New Client')}}</a>
                <a class="dropdown-item" href="#" data-url="{{ route('contacts.create') }}" data-ajax-popup="true" data-title="{{__('Create New Contact')}}">{{__('New Contact')}}</a>
            @endcan
            
            @can('create lead')
                <a class="dropdown-item" href="#" data-url="{{ route('leads.create') }}" data-ajax-popup="true" data-title="{{__('Create New Lead')}}">{{__('New Lead')}}</a>
            @endcan
            
            <div class="dropdown-divider"></div>
            
            @can('manage lead')
                <a class="dropdown-item" href="{{route('leads.board')}}">{{__('Leads Board')}}</a>
            @endcan
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
                <a class="nav-link {{(Request::segment(1)=='clients')?'active':''}}" data-toggle="tab" href="#clients" role="tab" aria-controls="clients" aria-selected="true">{{__('Clients')}}
                    <span class="badge badge-secondary">{{ count($clients) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{(Request::segment(1)=='contacts')?'active':''}}" data-toggle="tab" href="#contacts" role="tab" aria-controls="contacts" aria-selected="false">{{__('Contacts')}}
                    <span class="badge badge-secondary">{{ count($contacts) }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{(Request::segment(1)=='leads')?'active':''}}" data-toggle="tab" href="#leads" role="tab" aria-controls="leads" aria-selected="false">{{__('Leads')}}
                    <span class="badge badge-secondary">{{ $leads_count }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{(Request::segment(1)=='activity')?'active':''}}" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">{{__('Activity')}}</a>
            </li>
            </ul>
            <div class="tab-content">
            <div class="tab-pane fade show {{(Request::segment(1)=='clients')?'active':''}}" id="clients" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Clients')}}</h3>
                    @can('create client')
                    <button class="btn btn-round" data-url="{{ route('clients.create') }}" data-ajax-popup="true" data-title="{{__('Create New Client')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
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
                    <input type="search" class="form-control filter-list-input" placeholder="{{__('Filter Clients')}}" aria-label="{{__('Filter Clients')}}">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    @include('clients.index')
                </div>
                <!--end of content list body-->
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show {{(Request::segment(1)=='contacts')?'active':''}}" id="contacts" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Contacts')}}</h3>
                        @can('create client')
                        <button class="btn btn-round" data-url="{{ route('contacts.create') }}" data-ajax-popup="true" data-title="{{__('Create New Contact')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
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
                        <input type="search" class="form-control filter-list-input" placeholder="{{__('Filter Contacts')}}" aria-label="{{__('Filter Contacts')}}">
                        </div>
                    </form>
                    </div>
                    <!--end of content list head-->
                    <div class="content-list-body">
                        @include('contacts.index')
                    </div>
                    <!--end of content list body-->
                </div>
            <!--end of tab-->
            <div class="tab-pane fade show {{(Request::segment(1)=='leads')?'active':''}}" id="leads" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Leads')}}</h3>
                        @can('create lead')
                        <button class="btn btn-round" data-url="{{ route('leads.create') }}" data-ajax-popup="true" data-title="{{__('Create New Lead')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
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
                        <input type="search" class="form-control filter-list-input" placeholder="{{__('Filter Leads')}}" aria-label="{{__('Filter Leads')}}">
                        </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    @include('leads.index');
                </div>
                <!--end of content list body-->
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
@endsection
