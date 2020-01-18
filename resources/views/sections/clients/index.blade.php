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
            <li class="breadcrumb-item active" aria-current="page">{{__('User')}}</li>
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
                        <button class="btn btn-round" data-url="{{ route('leads.create') }}" data-ajax-popup="true" data-title="{{__('Create New Contact')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
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
            <form class="modal fade" id="user-manage-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Users</h5>
                    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
                    <i class="material-icons">close</i>
                    </button>
                </div>
                <!--end of modal head-->
                <div class="modal-body">
                    <div class="users-manage" data-filter-list="form-group-users">
                    <div class="mb-3">
                        <ul class="avatars text-center">

                        <li>
                            <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar" data-toggle="tooltip" data-title="Claire Connors" />
                        </li>

                        <li>
                            <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar" data-toggle="tooltip" data-title="Marcus Simmons" />
                        </li>

                        <li>
                            <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar" data-toggle="tooltip" data-title="Peggy Brown" />
                        </li>

                        <li>
                            <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar" data-toggle="tooltip" data-title="Harry Xai" />
                        </li>

                        </ul>
                    </div>
                    <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                        </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="Filter members" aria-label="Filter Members">
                    </div>
                    <div class="form-group-users">

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-1" checked>
                        <label class="custom-control-label" for="user-manage-1">
                            <span class="d-flex align-items-center">
                            <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Claire Connors</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-2" checked>
                        <label class="custom-control-label" for="user-manage-2">
                            <span class="d-flex align-items-center">
                            <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Marcus Simmons</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-3" checked>
                        <label class="custom-control-label" for="user-manage-3">
                            <span class="d-flex align-items-center">
                            <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Peggy Brown</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-4" checked>
                        <label class="custom-control-label" for="user-manage-4">
                            <span class="d-flex align-items-center">
                            <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Harry Xai</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-5">
                        <label class="custom-control-label" for="user-manage-5">
                            <span class="d-flex align-items-center">
                            <img alt="Sally Harper" src="assets/img/avatar-female-3.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Sally Harper</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-6">
                        <label class="custom-control-label" for="user-manage-6">
                            <span class="d-flex align-items-center">
                            <img alt="Ravi Singh" src="assets/img/avatar-male-3.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Ravi Singh</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-7">
                        <label class="custom-control-label" for="user-manage-7">
                            <span class="d-flex align-items-center">
                            <img alt="Kristina Van Der Stroem" src="assets/img/avatar-female-4.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Kristina Van Der Stroem</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-8">
                        <label class="custom-control-label" for="user-manage-8">
                            <span class="d-flex align-items-center">
                            <img alt="David Whittaker" src="assets/img/avatar-male-4.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">David Whittaker</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-9">
                        <label class="custom-control-label" for="user-manage-9">
                            <span class="d-flex align-items-center">
                            <img alt="Kerri-Anne Banks" src="assets/img/avatar-female-5.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Kerri-Anne Banks</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-10">
                        <label class="custom-control-label" for="user-manage-10">
                            <span class="d-flex align-items-center">
                            <img alt="Masimba Sibanda" src="assets/img/avatar-male-5.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Masimba Sibanda</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-11">
                        <label class="custom-control-label" for="user-manage-11">
                            <span class="d-flex align-items-center">
                            <img alt="Krishna Bajaj" src="assets/img/avatar-female-6.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Krishna Bajaj</span>
                            </span>
                        </label>
                        </div>

                        <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="user-manage-12">
                        <label class="custom-control-label" for="user-manage-12">
                            <span class="d-flex align-items-center">
                            <img alt="Kenny Tran" src="assets/img/avatar-male-6.jpg" class="avatar mr-2" />
                            <span class="h6 mb-0" data-filter-by="text">Kenny Tran</span>
                            </span>
                        </label>
                        </div>

                    </div>
                    </div>
                </div>
                <!--end of modal body-->
                <div class="modal-footer">
                    <button role="button" class="btn btn-primary" type="submit">
                    Done
                    </button>
                </div>
                </div>
            </div>
            </form>
            <form class="modal fade" id="project-edit-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Project</h5>
                    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
                    <i class="material-icons">close</i>
                    </button>
                </div>
                <!--end of modal head-->
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" id="project-edit-details-tab" data-toggle="tab" href="#project-edit-details" role="tab" aria-controls="project-edit-details" aria-selected="true">Details</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="project-edit-members-tab" data-toggle="tab" href="#project-edit-members" role="tab" aria-controls="project-edit-members" aria-selected="false">Members</a>
                    </li>
                </ul>
                <div class="modal-body">
                    <div class="tab-content">
                    <div class="tab-pane fade show active" id="project-edit-details" role="tabpanel">
                        <h6>General Details</h6>
                        <div class="form-group row align-items-center">
                        <label class="col-3">Name</label>
                        <input class="form-control col" type="text" value="Brand Concept and Design" name="project-name" />
                        </div>
                        <div class="form-group row">
                        <label class="col-3">Description</label>
                        <textarea class="form-control col" rows="3" placeholder="Project description" name="project-description">Research, ideate and present brand concepts for client consideration</textarea>
                        </div>
                        <hr>
                        <h6>Timeline</h6>
                        <div class="form-group row align-items-center">
                        <label class="col-3">Start Date</label>
                        <input class="form-control col" type="text" name="project-start" placeholder="Select a date" data-flatpickr data-default-date="2021-04-21" data-alt-input="true" />
                        </div>
                        <div class="form-group row align-items-center">
                        <label class="col-3">Due Date</label>
                        <input class="form-control col" type="text" name="project-due" placeholder="Select a date" data-flatpickr data-default-date="2021-09-15" data-alt-input="true" />
                        </div>
                        <div class="alert alert-warning text-small" role="alert">
                        <span>You can change due dates at any time.</span>
                        </div>
                        <hr>
                        <h6>Visibility</h6>
                        <div class="row">
                        <div class="col">
                            <div class="custom-control custom-radio">
                            <input type="radio" id="visibility-everyone" name="visibility" class="custom-control-input" checked>
                            <label class="custom-control-label" for="visibility-everyone">Everyone</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="custom-control custom-radio">
                            <input type="radio" id="visibility-members" name="visibility" class="custom-control-input">
                            <label class="custom-control-label" for="visibility-members">Members</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="custom-control custom-radio">
                            <input type="radio" id="visibility-me" name="visibility" class="custom-control-input">
                            <label class="custom-control-label" for="visibility-me">Just me</label>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="project-edit-members" role="tabpanel">
                        <div class="users-manage" data-filter-list="form-group-users">
                        <div class="mb-3">
                            <ul class="avatars text-center">

                            <li>
                                <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar" data-toggle="tooltip" data-title="Claire Connors" />
                            </li>

                            <li>
                                <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar" data-toggle="tooltip" data-title="Marcus Simmons" />
                            </li>

                            <li>
                                <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar" data-toggle="tooltip" data-title="Peggy Brown" />
                            </li>

                            <li>
                                <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar" data-toggle="tooltip" data-title="Harry Xai" />
                            </li>

                            </ul>
                        </div>
                        <div class="input-group input-group-round">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">filter_list</i>
                            </span>
                            </div>
                            <input type="search" class="form-control filter-list-input" placeholder="Filter members" aria-label="Filter Members">
                        </div>
                        <div class="form-group-users">

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="project-user-1" checked>
                            <label class="custom-control-label" for="project-user-1">
                                <span class="d-flex align-items-center">
                                <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Claire Connors</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="project-user-2" checked>
                            <label class="custom-control-label" for="project-user-2">
                                <span class="d-flex align-items-center">
                                <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Marcus Simmons</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="project-user-3" checked>
                            <label class="custom-control-label" for="project-user-3">
                                <span class="d-flex align-items-center">
                                <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Peggy Brown</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="project-user-4" checked>
                            <label class="custom-control-label" for="project-user-4">
                                <span class="d-flex align-items-center">
                                <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Harry Xai</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="project-user-5">
                            <label class="custom-control-label" for="project-user-5">
                                <span class="d-flex align-items-center">
                                <img alt="Sally Harper" src="assets/img/avatar-female-3.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Sally Harper</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="project-user-6">
                            <label class="custom-control-label" for="project-user-6">
                                <span class="d-flex align-items-center">
                                <img alt="Ravi Singh" src="assets/img/avatar-male-3.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Ravi Singh</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="project-user-7">
                            <label class="custom-control-label" for="project-user-7">
                                <span class="d-flex align-items-center">
                                <img alt="Kristina Van Der Stroem" src="assets/img/avatar-female-4.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Kristina Van Der Stroem</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="project-user-8">
                            <label class="custom-control-label" for="project-user-8">
                                <span class="d-flex align-items-center">
                                <img alt="David Whittaker" src="assets/img/avatar-male-4.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">David Whittaker</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="project-user-9">
                            <label class="custom-control-label" for="project-user-9">
                                <span class="d-flex align-items-center">
                                <img alt="Kerri-Anne Banks" src="assets/img/avatar-female-5.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Kerri-Anne Banks</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="project-user-10">
                            <label class="custom-control-label" for="project-user-10">
                                <span class="d-flex align-items-center">
                                <img alt="Masimba Sibanda" src="assets/img/avatar-male-5.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Masimba Sibanda</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="project-user-11">
                            <label class="custom-control-label" for="project-user-11">
                                <span class="d-flex align-items-center">
                                <img alt="Krishna Bajaj" src="assets/img/avatar-female-6.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Krishna Bajaj</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="project-user-12">
                            <label class="custom-control-label" for="project-user-12">
                                <span class="d-flex align-items-center">
                                <img alt="Kenny Tran" src="assets/img/avatar-male-6.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Kenny Tran</span>
                                </span>
                            </label>
                            </div>

                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                <!--end of modal body-->
                <div class="modal-footer">
                    <button role="button" class="btn btn-primary" type="submit">
                    Save
                    </button>
                </div>
                </div>
            </div>
            </form>

            <form class="modal fade" id="task-add-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Task</h5>
                    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
                    <i class="material-icons">close</i>
                    </button>
                </div>
                <!--end of modal head-->
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" id="task-add-details-tab" data-toggle="tab" href="#task-add-details" role="tab" aria-controls="task-add-details" aria-selected="true">Details</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="task-add-members-tab" data-toggle="tab" href="#task-add-members" role="tab" aria-controls="task-add-members" aria-selected="false">Members</a>
                    </li>
                </ul>
                <div class="modal-body">
                    <div class="tab-content">
                    <div class="tab-pane fade show active" id="task-add-details" role="tabpanel">
                        <h6>General Details</h6>
                        <div class="form-group row align-items-center">
                        <label class="col-3">Name</label>
                        <input class="form-control col" type="text" placeholder="Task name" name="task-name" />
                        </div>
                        <div class="form-group row">
                        <label class="col-3">Description</label>
                        <textarea class="form-control col" rows="3" placeholder="Task description" name="task-description"></textarea>
                        </div>
                        <hr>
                        <h6>Timeline</h6>
                        <div class="form-group row align-items-center">
                        <label class="col-3">Start Date</label>
                        <input class="form-control col" type="text" name="task-start" placeholder="Select a date" data-flatpickr data-default-date="2021-04-21" data-alt-input="true" />
                        </div>
                        <div class="form-group row align-items-center">
                        <label class="col-3">Due Date</label>
                        <input class="form-control col" type="text" name="task-due" placeholder="Select a date" data-flatpickr data-default-date="2021-09-15" data-alt-input="true" />

                        </div>
                        <div class="alert alert-warning text-small" role="alert">
                        <span>You can change due dates at any time.</span>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="task-add-members" role="tabpanel">
                        <div class="users-manage" data-filter-list="form-group-users">
                        <div class="mb-3">
                            <ul class="avatars text-center">

                            <li>
                                <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar" data-toggle="tooltip" data-title="Claire Connors" />
                            </li>

                            <li>
                                <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar" data-toggle="tooltip" data-title="Marcus Simmons" />
                            </li>

                            <li>
                                <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar" data-toggle="tooltip" data-title="Peggy Brown" />
                            </li>

                            <li>
                                <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar" data-toggle="tooltip" data-title="Harry Xai" />
                            </li>

                            </ul>
                        </div>
                        <div class="input-group input-group-round">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">filter_list</i>
                            </span>
                            </div>
                            <input type="search" class="form-control filter-list-input" placeholder="Filter members" aria-label="Filter Members">
                        </div>
                        <div class="form-group-users">

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-1" checked>
                            <label class="custom-control-label" for="task-user-1">
                                <span class="d-flex align-items-center">
                                <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Claire Connors</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-2" checked>
                            <label class="custom-control-label" for="task-user-2">
                                <span class="d-flex align-items-center">
                                <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Marcus Simmons</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-3" checked>
                            <label class="custom-control-label" for="task-user-3">
                                <span class="d-flex align-items-center">
                                <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Peggy Brown</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-4" checked>
                            <label class="custom-control-label" for="task-user-4">
                                <span class="d-flex align-items-center">
                                <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Harry Xai</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-5">
                            <label class="custom-control-label" for="task-user-5">
                                <span class="d-flex align-items-center">
                                <img alt="Sally Harper" src="assets/img/avatar-female-3.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Sally Harper</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-6">
                            <label class="custom-control-label" for="task-user-6">
                                <span class="d-flex align-items-center">
                                <img alt="Ravi Singh" src="assets/img/avatar-male-3.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Ravi Singh</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-7">
                            <label class="custom-control-label" for="task-user-7">
                                <span class="d-flex align-items-center">
                                <img alt="Kristina Van Der Stroem" src="assets/img/avatar-female-4.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Kristina Van Der Stroem</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-8">
                            <label class="custom-control-label" for="task-user-8">
                                <span class="d-flex align-items-center">
                                <img alt="David Whittaker" src="assets/img/avatar-male-4.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">David Whittaker</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-9">
                            <label class="custom-control-label" for="task-user-9">
                                <span class="d-flex align-items-center">
                                <img alt="Kerri-Anne Banks" src="assets/img/avatar-female-5.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Kerri-Anne Banks</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-10">
                            <label class="custom-control-label" for="task-user-10">
                                <span class="d-flex align-items-center">
                                <img alt="Masimba Sibanda" src="assets/img/avatar-male-5.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Masimba Sibanda</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-11">
                            <label class="custom-control-label" for="task-user-11">
                                <span class="d-flex align-items-center">
                                <img alt="Krishna Bajaj" src="assets/img/avatar-female-6.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Krishna Bajaj</span>
                                </span>
                            </label>
                            </div>

                            <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="task-user-12">
                            <label class="custom-control-label" for="task-user-12">
                                <span class="d-flex align-items-center">
                                <img alt="Kenny Tran" src="assets/img/avatar-male-6.jpg" class="avatar mr-2" />
                                <span class="h6 mb-0" data-filter-by="text">Kenny Tran</span>
                                </span>
                            </label>
                            </div>

                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                <!--end of modal body-->
                <div class="modal-footer">
                    <button role="button" class="btn btn-primary" type="submit">
                    Create Task
                    </button>
                </div>
                </div>
            </div>
            </form>

        </div>
    </div>
</div>
@endsection
