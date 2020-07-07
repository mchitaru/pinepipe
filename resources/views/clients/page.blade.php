@extends('layouts.app')

@php clock()->startEvent('clientsection.index', "Display client section"); @endphp

@push('stylesheets')
@endpush

@push('scripts')

<script>

$(function() {

    localStorage.setItem('sort', 'name');
    localStorage.setItem('dir', 'asc');
    localStorage.setItem('filter', '');
    localStorage.setItem('tag', '');

    updateFilters();

});

</script>

@endpush

@section('page-title')
    {{__('Clients')}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="page-header">
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="clients" role="tabpanel">
                    <div class="row content-list-head">
                        <div class="col-auto">
                            <h3>{{__('Clients')}}</h3>
                            @can('create client')
                            <a href="{{ route('clients.create') }}" class="btn btn-primary btn-round" data-remote="true" data-type="text">
                                <i class="material-icons">add</i>
                            </a>
                            @endcan
                        </div>
                        <div class="col-md-auto">
                            <div class="input-group input-group-round">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="material-icons">filter_list</i>
                                </span>
                            </div>
                            <input type="search" class="form-control filter-input" placeholder="{{__('Filter Clients')}}" aria-label="{{__('Filter Clients')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row content-list-head">
                        <div class="filter-container col-auto">
                            <div class="filter-controls">
                                <div>{{__('Sort')}}:</div>
                                <a class="order" href="#" data-sort="name">{{__('Name')}}</a>
                                <a class="order" href="#" data-sort="email">{{__('Email')}}</a>
                            </div>
                        </div>
                    </div>
                    <!--end of content list head-->
                    <div class="content-list-body filter-list paginate-container">
                        <div class="w-100 row justify-content-center pt-3">
                            @include('partials.spinner')
                        </div>
                    </div>
                    <!--end of content list body-->
                </div>
                <!--end of tab-->
            </div>
        </div>
    </div>
</div>

@endsection

@php clock()->endEvent('clientsection.index'); @endphp
