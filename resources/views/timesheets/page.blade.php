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
        localStorage.setItem('from', '{{ now()->firstOfMonth()->toDateString() }}');
        localStorage.setItem('until', '{{ now()->toDateString() }}');

        updateFilters();

        loadContent($('.paginate-container:visible'));        
    });

    function initReportURL(){

        var url = new URL(window.location.href);

        from = url.searchParams.has("from") ? url.searchParams.get("from") : null;
        until = url.searchParams.has("until") ? url.searchParams.get("until") : null;
        filter = url.searchParams.has("filter") ? url.searchParams.get("filter") : null;

        url = new URL($('#report').attr('href'));

        if(from)
            url.searchParams.set("from", from);
        else
            url.searchParams.delete('from');

        if(until)            
            url.searchParams.set("until", until);
        else
            url.searchParams.delete('until');

        if(filter)
            url.searchParams.set("filter", filter);
        else
            url.searchParams.delete('filter');
        
        $("#report").attr("href", url);
    }
    
    document.addEventListener("paginate-load", function(e) {
        initReportURL();
    });


    document.addEventListener("paginate-filter", function(e) {
        initReportURL();
    });

    document.addEventListener("paginate-from", function(e) {
        initReportURL();
    });

    document.addEventListener("paginate-until", function(e) {
        initReportURL();
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
                    <div class="col">
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
                    <div class="dropdown col-sm-auto">
                        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('timesheets.report') }}" id = "report" >
                                {{__('Download report')}}
                            </a>
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
                    <div class="filter-container col-auto align-items-center">
                        <div class="filter-controls">
                            <div>{{__('From')}}:</div>
                        </div>
                        <input type="date" class="start filter-from form-control col bg-white" placeholder = "..." data-locale = {{\Auth::user()->locale}} data-week-numbers = 'true' data-alt-input = 'true'>
                    </div>
                    <div class="filter-container col-auto align-items-center">
                        <div class="filter-controls">
                            <div>{{__('Until')}}:</div>
                        </div>
                        <input type="date" class="end filter-until form-control col bg-white" placeholder = "..." data-locale = {{\Auth::user()->locale}} data-week-numbers = 'true' data-alt-input = 'true'>
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
