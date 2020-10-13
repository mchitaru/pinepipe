@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')
<script>

    $(function() {
    
        localStorage.setItem('sort', 'date');
        localStorage.setItem('dir', 'desc');
        localStorage.setItem('filter', '');
        localStorage.setItem('tag', '');

        updateFilters();

        loadContent($('.paginate-container:visible'));        
    });
    
</script>    
@endpush

@section('page-title')
    {{__('Timesheets')}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="page-header">
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="timesheets" role="tabpanel">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Timesheets')}}</h3>
                        @can('create', 'App\Timesheet')
                        <a href="{{ route('timesheets.create') }}" class="btn btn-primary btn-round" data-remote="true" data-type="text">
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
                        <input type="search" class="form-control filter-input" placeholder="{{__('Filter Timesheets')}}" aria-label="{{__('Filter Timesheets')}}">
                        </div>
                    </div>
                </div>
                <div class="row content-list-filter align-items-center">
                    <div class="filter-container col-auto align-items-center">
                        <div class="filter-controls">
                            <div>{{__('Sort')}}:</div>
                        </div>
                        <div class="filter-controls">
                            <a class="order" href="#" data-sort="date">{{__('Date')}}</a>
                        </div>
                    </div>
                </div>
                <!--end of content list head-->
                @can('viewAny', 'App\Timesheet')
                <div class="content-list-body filter-list paginate-container">
                </div>
                @endcan
            </div>
            <!--end of tab-->
        </div>
    </div>
</div>
</div>
@endsection
