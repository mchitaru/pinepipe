@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')
<script>

    $(function() {

        localStorage.setItem('sort', 'due_date');
        localStorage.setItem('dir', 'asc');
        localStorage.setItem('filter', '');
        localStorage.setItem('tag', 'unpaid');

        updateFilters();

        loadContent($('.paginate-container:visible'));
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
                    <div class="col-12 col-md-auto">
                        <h3>{{__('Invoices')}}</h3>
                        @can('create', 'App\Invoice')
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
                <div class="row content-list-filter align-items-center">
                    <div class="filter-container col-auto align-items-center">
                        <div class="filter-controls">
                            <div>{{__('Sort')}}:</div>
                        </div>
                        <div class="filter-controls">
                            <a class="order" href="#" data-sort="due_date">{{__('Due Date (inv)')}}</a>
                        </div>
                    </div>
                    <div class="filter-container col-auto align-items-center">
                        <div class="filter-tags">
                            <div>{{__('Tag')}}:</div>
                        </div>
                        <div class="filter-tags">
                            <div class="tag filter" data-filter="unpaid">{{trans_choice('Outstanding', 2)}}</div>
                            <div class="tag filter" data-filter="paid">{{trans_choice('Paid', 2)}}</div>
                            <div class="tag filter" data-filter="all">{{trans_choice('All', 2)}}</div>
                        </div>
                    </div>
                </div>
                <!--end of content list head-->
                @can('viewAny', 'App\Invoice')
                <div class="content-list-body filter-list paginate-container">
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
