@extends('layouts.app')

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

    //     if(window.location.hash)
    //     {
    //         var hash = window.location.hash ? window.location.hash : '#invoices';

    //         $('.nav-tabs a[href="' + hash + '"]').tab('show');
    //     }
    // });

</script>

@endpush

@section('page-title')
    {{__('Finance')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Finance')}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item" href="#">{{__('New Contract')}}</a>
            <a class="dropdown-item" href="#">{{__('New Invoice')}}</a>

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
            <a class="nav-link" data-toggle="tab" href="#proposals" role="tab" aria-controls="proposals" aria-selected="true">{{__('Proposals')}}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#contracts" role="tab" aria-controls="contracts" aria-selected="false">{{__('Contracts')}}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{(Request::segment(1)=='invoices')?'active':''}}" data-toggle="tab" href="#invoices" role="tab" aria-controls="invoices" aria-selected="false">{{__('Invoices')}}
                <span class="badge badge-secondary">{{ count($invoices) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{(Request::segment(1)=='expenses')?'active':''}}" data-toggle="tab" href="#expenses" role="tab" aria-controls="expenses" aria-selected="false">{{__('Expenses')}}
                <span class="badge badge-secondary">{{ count($expenses) }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{(Request::segment(1)=='activity')?'active':''}}" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">{{__('Activity')}}</a>
        </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show {{(Request::segment(1)=='invoices')?'active':''}}" id="invoices" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Invoices')}}</h3>
                        @can('create invoice')
                        <button class="btn btn-round" data-url="{{ route('invoices.create') }}" data-ajax-popup="true" data-title="{{__('Create New Invoice')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
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
                        <input type="search" class="form-control filter-list-input" placeholder="{{__('Filter Invoices')}}" aria-label="{{__('Filter Invoices')}}">
                        </div>
                    </form>
                    </div>
                    <!--end of content list head-->
                    <div class="content-list-body">
                        @include('invoices.index')
                    </div>
                    <!--end of content list body-->
                </div>
            <!--end of tab-->
            <div class="tab-pane fade show {{Request::segment(1)=='expenses'?'active':''}}" id="expenses" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Expenses')}}</h3>
                        @can('create invoice')
                        <button class="btn btn-round" data-url="{{ route('expenses.create') }}" data-ajax-popup="true" data-title="{{__('Add New Expense')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
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
                        <input type="search" class="form-control filter-list-input" placeholder="{{__('Filter Expenses')}}" aria-label="{{__('Filter Expenses')}}">
                        </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    @include('expenses.index')
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
</div>
@endsection
