@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')
<script>

    $(function() {
    
        localStorage.setItem('sort', '');
        localStorage.setItem('dir', '');
        localStorage.setItem('filter', '');
        localStorage.setItem('tag', 'all');
        
        updateFilters();

    });
    
</script>    
@endpush

@section('page-title')
    {{__('Invoices')}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="page-header">
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="invoices" role="tabpanel">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Invoices')}}</h3>
                        @can('create invoice')
                        <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-round" data-remote="true" data-type="text">
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
                            <input type="search" class="form-control filter-input" placeholder="{{__('Filter Invoices')}}" aria-label="{{__('Filter Invoices')}}">
                        </div>
                    </div>
                </div>
                <div class="row content-list-head">
                    <div class="filter-container col-auto">
                        <div class="filter-tags">
                            <div>{{__('Tag')}}:</div>
                            <div class="tag filter" data-filter="all">{{__('All')}}</div>
                            <div class="tag filter" data-filter="pending">{{__('Pending')}}</div>
                            <div class="tag filter" data-filter="outstanding">{{__('Outstanding')}}</div>
                            <div class="tag filter" data-filter="partial payment">{{__('Partial Payment')}}</div>
                            <div class="tag filter" data-filter="paid">{{__('Paid')}}</div>
                        </div>                                           
                    </div>
                    </div>
                    <!--end of content list head-->
                    @can('view invoice')
                    <div class="content-list-body filter-list paginate-container">
                        <div class="w-100 row justify-content-center pt-3">
                            @include('partials.spinner')
                        </div>
                    </div>
                    @endcan
                    <!--end of content list body-->
                </div>
            <!--end of tab-->
        </div>
    </div>
</div>
</div>
@endsection
