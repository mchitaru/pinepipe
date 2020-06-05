@extends('layouts.app')

@php clock()->startEvent('projectsection.index', "Display project section"); @endphp

@push('stylesheets')
@endpush

@push('scripts')
<script>

    $(function() {
    
        localStorage.setItem('sort', 'name');
        localStorage.setItem('dir', 'asc');
        localStorage.setItem('filter', '');
        localStorage.setItem('tag', 'active');

        updateFilters();

    });
    
</script>    
@endpush

@section('page-title')
    {{__('Projects')}}
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
                            <input type="search" class="form-control filter-input" placeholder="{{__('Filter projects')}}" aria-label="Filter Projects">
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
                            <div class="tag filter" data-filter="active">{{__('Active')}}</div>
                            <div class="tag filter" data-filter="archived">{{__('Archived')}}</div>
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
