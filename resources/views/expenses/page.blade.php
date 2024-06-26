@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')
<script>

    $(function() {
    
        localStorage.setItem('sort', '');
        localStorage.setItem('dir', '');
        localStorage.setItem('filter', '');
        localStorage.setItem('tag', '');

        updateFilters();

        loadContent($('.paginate-container:visible'));        
    });
    
</script>    
@endpush

@section('page-title')
    {{__('Expenses')}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="page-header">
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="expenses" role="tabpanel">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Expenses')}}</h3>
                        @can('create', 'App\Expense')
                        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-round" data-remote="true" data-type="text">
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
                        <input type="search" class="form-control filter-input" placeholder="{{__('Filter Expenses')}}" aria-label="{{__('Filter Expenses')}}">
                        </div>
                    </div>
                </div>
                <!--end of content list head-->
                @can('viewAny', 'App\Expense')
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
