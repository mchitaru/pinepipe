@extends('layouts.app')

@php clock()->startEvent('leads.page', "Display lead page"); @endphp

@php
use Carbon\Carbon;
@endphp

@push('stylesheets')
@endpush

@push('scripts')
<script type="text/javascript" src="{{ asset('assets/js/draggable.bundle.min.js') }}"></script>

<script>
function initLeadCards() {

    const sortableLists = new Draggable.Sortable(document.querySelectorAll('div.kanban-board'), {
        draggable: '.kanban-col:not(:last-child)',
        handle: '.card-list-header',
        delay: 200,
    });

    const sortableCards = new Draggable.Sortable(document.querySelectorAll('.kanban-col .card-list-body'), {
        plugins: [Draggable.Plugins.SwapAnimation],
        draggable: '.card-kanban',
        handle: '.card-kanban',
        appendTo: 'body',
        delay: 200,
    });

    sortableLists.on('sortable:stop', (evt) => {

        var order = [];

        var list = sortableLists.getDraggableElementsForContainer(evt.newContainer);

        for (var i = 0; i < list.length; i++)
        {
            if(list[i].attributes['data-id']){

                order[i] = list[i].attributes['data-id'].value;
            }
        }

        $.ajax({
            url: '{{route('stages.order')}}',
            type: 'POST',
            data: {order: order, "_token": $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {

                if(response.is_success){

                    toastrs('{{__('Stage order successfully updated.')}}', 'success');
                }
            },
            error: function (data) {
                toastrs('{{__("You dont have the right to perform this operation!")}}', 'danger');
            }
        });
    });

    sortableCards.on('sortable:stop', (evt) => {

        var order = [];

        var list = sortableCards.getDraggableElementsForContainer(evt.newContainer);

        for (var i = 0; i < list.length; i++)
        {
            order[i] = list[i].attributes['data-id'].value;
        }

        var lead_id = evt.newContainer.children[evt.newIndex].attributes['data-id'].value;
        var lead_value = parseInt($(evt.newContainer.children[evt.newIndex]).find('.price').attr('data-id')) || 0;

        var old_stage_id = evt.oldContainer.attributes['data-id'].value;
        var new_stage_id = evt.newContainer.attributes['data-id'].value;

        var total_old = parseInt($(evt.oldContainer).prev().find('.total').attr('data-id')) || 0;
        var total_new = parseInt($(evt.newContainer).prev().find('.total').attr('data-id')) || 0;

        if(old_stage_id != new_stage_id)
        {
            total_old -= lead_value;
            total_new += lead_value;
        }

        $(evt.oldContainer).prev().find('.total').attr('data-id', total_old);
        $(evt.newContainer).prev().find('.total').attr('data-id', total_new);

        $.ajax({
            url: '{{route('leads.order')}}',
            type: 'POST',
            data: {lead_id: lead_id, stage_id: new_stage_id, order: order, total_old: total_old, total_new: total_new, "_token": $('meta[name="csrf-token"]').attr('content')},
            success: function (response) {

                $(evt.oldContainer).prev().find('.count').text('(' + sortableCards.getDraggableElementsForContainer(evt.oldContainer).length + ')');
                $(evt.newContainer).prev().find('.count').text('(' + sortableCards.getDraggableElementsForContainer(evt.newContainer).length + ')');

                $(evt.oldContainer).prev().find('.total').text(response.total_old);
                $(evt.newContainer).prev().find('.total').text(response.total_new);

                if(response.is_success){
                    toastrs('{{__('Lead successfully updated.')}}', 'success');
                }
            },
            error: function (data) {
                /* console.log('error'); */
            }
        });
    });

    $('.card-list .dropdown').on('show.bs.dropdown', function() {        
        $('body').append($(this).children('.dropdown-menu').css({
            position: 'absolute',
            left: $('.dropdown-menu').offset().left,
            top: $('.dropdown-menu').offset().top
        }).detach());
    });
};
    $(function() {
    
        localStorage.setItem('sort', 'order');
        localStorage.setItem('dir', 'asc');
        localStorage.setItem('filter', '');
        localStorage.setItem('tag', 'active');

        updateFilters();
    });

    document.addEventListener("paginate-sort", function(e) {
        initLeadCards();
    });

    document.addEventListener("paginate-load", function(e) {
        initLeadCards();
    });

    document.addEventListener("paginate-tag", function(e) {
        initLeadCards();
    });

</script>

@endpush

@section('page-title')
    {{__('Lead Board')}}
@endsection

@section('content')

    <div class="container-kanban" data-filter-list="card-list-body">
        <div class="container-fluid page-header justify-content-between mb-0">
            <div class="row content-list-head">
                <div class="col-12 col-md-auto">
                    <h3>{{__('Leads')}}</h3>
                    @can('create lead')
                    <a href="{{ route('leads.create') }}" class="btn btn-primary btn-round" data-remote="true" data-type="text">
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
                    <input type="search" class="form-control filter-list-input" placeholder="{{__('Filter Leads')}}" aria-label="{{__('Filter Leads')}}">
                    </div>
                </form>
            </div>
            <div class="row content-list-filter">
                <div class="filter-container col-auto">
                    <div class="filter-controls">
                        <div>{{__('Sort')}}:</div>
                    </div>                    
                    <div class="filter-controls">
                        <a class="order" href="#" data-sort="order">{{__('Order')}}</a>
                        <a class="order" href="#" data-sort="name">{{__('Name')}}</a>
                        <a class="order" href="#" data-sort="price">{{__('Value')}}</a>
                    </div>
                </div>
                <div class="filter-container col-auto">
                    <div class="filter-tags">
                        <div>{{__('Tag')}}:</div>
                    </div>                    
                    <div class="filter-tags">
                        <div class="tag filter" data-filter="active">{{__('Active')}}</div>
                        <div class="tag filter" data-filter="archived">{{__('Archived')}}</div>
                    </div>                                           
                </div>
            </div>
        </div>
        <div class="kanban-board container-fluid filter-list paginate-container">
            <div class="w-100 row justify-content-center">
                @include('partials.spinner')
            </div>
        </div>
    </div>
@endsection

@php clock()->endEvent('leads.page'); @endphp
