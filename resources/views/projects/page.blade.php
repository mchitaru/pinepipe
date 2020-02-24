@extends('layouts.app')

@php clock()->startEvent('projectsection.index', "Display project section"); @endphp

@push('stylesheets')
@endpush

@push('scripts')
<script>

    $(function() {
    
        localStorage.setItem('sort', 'name');
        localStorage.setItem('dir', 'asc');
        localStorage.setItem('filter', null);
        localStorage.setItem('tag', 'active');

        updateFilters();

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
        <div class="tab-pane fade show active" id="projects" role="tabpanel">
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
                    <div class="col-md-auto">
                        <div class="input-group input-group-round">
                            <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">filter_list</i>
                            </span>
                            </div>
                            <input type="search" class="form-control filter-input" placeholder="Filter projects" aria-label="Filter Projects">
                        </div>
                    </div>
                </div>
                <div class="row content-list-head">
                    <div class="filter-container col-auto">
                        <div class="filter-controls">
                            <div>{{__('Sort')}}:</div>
                            <a class="sort" href="#" data-sort="name">{{__('Name')}}</a>
                            <a class="sort" href="#" data-sort="due_date">{{__('Date')}}</a>
                        </div>    
                        <div class="filter-tags">
                            <div>{{__('Tag')}}:</div>
                            <div class="tag filter" data-filter="active">{{__('active')}}</div>
                            <div class="tag filter" data-filter="archived">{{__('archived')}}</div>
                        </div>                                           
                    </div>
                </div>
                <!--end of content list head-->
                <div class="content-list-body filter-list row paginate-container">@include('projects.index')</div>
            <!--end of content list body-->
            </div>
            <!--end of content list-->
        </div>
        <!--end of tab-->
        </div>
    </div>
</div>
@endsection

@php clock()->endEvent('projectsection.index'); @endphp
