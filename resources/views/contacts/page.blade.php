@extends('layouts.app')

@php clock()->startEvent('contactssection.index', "Display contacts section"); @endphp

@push('stylesheets')
@endpush

@push('scripts')
<script>

    $(function() {

        localStorage.setItem('sort', 'name');
        localStorage.setItem('dir', 'asc');
        localStorage.setItem('filter', null);
        localStorage.setItem('tag', null);

        updateFilters();

    });

</script>
@endpush

@section('page-title')
    {{__('Contacts')}}
@endsection

@section('breadcrumb')

<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Contacts')}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            @can('create contact')
                <a class="dropdown-item" href="{{ route('contacts.create') }}" data-remote="true" data-type="text">{{__('New Contact')}}</a>
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
            <div class="tab-content">
                <div class="tab-pane fade show active" id="contacts" role="tabpanel">
                    <div class="row content-list-head">
                        <div class="col-auto">
                            <h3>{{__('Contacts')}}</h3>
                            @can('create contact')
                            <a href="{{ route('contacts.create') }}" class="btn btn-round" data-remote="true" data-type="text">
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
                                <input type="search" class="form-control filter-input" placeholder="{{__('Filter Contacts')}}" aria-label="{{__('Filter Contacts')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row content-list-head">
                        <div class="filter-container col-auto">
                            <div class="filter-controls">
                                <div>Sort by:</div>
                                <a class="sort" href="#" data-sort="name">Name</a>
                                <a class="sort" href="#" data-sort="email">Email</a>
                                <a class="sort" href="#" data-sort="phone">Phone</a>
                            </div>     
                        </div>
                    </div>
                    <!--end of content list head-->
                    <div class="content-list-body filter-list paginate-container">@include('contacts.index')</div>
                    <!--end of content list body-->
                </div>
            <!--end of tab-->
            </div>
        </div>
    </div>
</div>
@endsection

@php clock()->endEvent('contactssection.index'); @endphp
