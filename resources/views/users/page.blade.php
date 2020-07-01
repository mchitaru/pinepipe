@extends('layouts.app')

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
    {{__('Users')}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="page-header">
        </div>
        <div class="tab-content">
        <div class="tab-pane fade show active" id="users" role="tabpanel">
            <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Users')}}</h3>
                    @can('create user')
                        <a href="{{ route('users.create') }}" class="btn btn-round" data-remote="true" data-type="text">
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
                    <input type="search" class="form-control filter-input" placeholder="{{__("Filter users")}}" aria-label="Filter Users">
                    </div>
                </div>
            </div>
            @if(\Auth::user()->type!='super admin')
            <div class="row content-list-head">
                <div class="filter-container col-auto">
                    <div class="filter-controls">
                        <div>{{__('Sort')}}:</div>
                        <a class="sort" href="#" data-sort="name">{{__('Name')}}</a>
                        <a class="sort" href="#" data-sort="email">{{__('Email')}}</a>
                    </div>
                </div>
            </div>
            @endif
            <!--end of content list head-->
            <div class="content-list-body filter-list paginate-container">
                <div class="w-100 row justify-content-center pt-3">
                    @include('partials.spinner')
                </div>
            </div>
            </div>
            <!--end of modal body-->
        </div>
    </div>
</div>
</div>
@endsection
