@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('page-title')
    {{__('Roles')}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="page-header">
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="roles" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>{{__('Roles')}}</h3>
                    @can('create user')
                        <a href="{{ route('roles.create') }}" class="btn btn-round" data-remote="true" data-type="text">
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
                    <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter users")}}" aria-label="Filter Users">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">@include('roles.index')</div>
            </div>    
        </div>
    </div>
</div>
</div>
@endsection
