@extends('layouts.app')

@php clock()->startEvent('clientsection.index', "Display client section"); @endphp

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

    var hash = window.location.hash ? window.location.hash : '#clients';

    $('.nav-tabs a[href="' + hash + '"]').tab('show');

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
                <a class="dropdown-item" href="{{ route('clients.create') }}" data-remote="true" data-type="text">{{__('New Client')}}</a>
            @endcan
            
            <a class="dropdown-item disabled" href="#">{{__('New Proposal')}}</a>
            <a class="dropdown-item disabled" href="#">{{__('New Contract')}}</a>            

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
                <a class="nav-link " data-toggle="tab" href="#clients" role="tab" aria-controls="clients" aria-selected="true">{{__('Clients')}}
                    <span class="badge badge-secondary">{{ $clients->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#proposals" role="tab" aria-controls="proposals" aria-selected="true">{{__('Proposals')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#contracts" role="tab" aria-controls="contracts" aria-selected="false">{{__('Contracts')}}
                </a>
            </li>    
            </ul>
            <div class="tab-content">
            <div class="tab-pane fade show " id="clients" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Clients')}}</h3>
                    @can('create client')
                    <a href="{{ route('clients.create') }}" class="btn btn-round" data-remote="true" data-type="text">
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
        </div>
    </div>
</div>

@endsection

@php clock()->endEvent('clientsection.index'); @endphp
