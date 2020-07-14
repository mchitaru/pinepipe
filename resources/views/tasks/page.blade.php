@extends('layouts.app')

@php clock()->startEvent('tasks.page', "Display task page"); @endphp

@php
use Carbon\Carbon;
use App\Project;
@endphp

@push('stylesheets')
@endpush

@push('scripts')

<script type="text/javascript" src="{{ asset('assets/js/draggable.bundle.min.js') }}"></script>

<script>

function initTaskCards() {

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
                    toastrs('Stage order successfully updated.', 'success');
                }
            },
            error: function (data) {

                toastrs('This operation is not allowed!', 'danger');
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

        var task_id = evt.newContainer.children[evt.newIndex].attributes['data-id'].value;
        var stage_id = evt.newContainer.attributes['data-id'].value;

        $(evt.oldContainer).prev().find('.count').text('(' + sortableCards.getDraggableElementsForContainer(evt.oldContainer).length + ')');
        $(evt.newContainer).prev().find('.count').text('(' + sortableCards.getDraggableElementsForContainer(evt.newContainer).length + ')');

        $.ajax({
            url: '{{route('tasks.order')}}',
            type: 'POST',
            data: {task_id: task_id, stage_id: stage_id, order: order, "_token": $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                /* console.log('success'); */

                if(data.is_success){
                    toastrs('{{__('Task successfully updated.')}}', 'success');
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
    localStorage.setItem('tag', 'mine');

    updateFilters();
});

document.addEventListener("paginate-sort", function(e) {
    initTaskCards();
});

document.addEventListener("paginate-load", function(e) {
    initTaskCards();
});

</script>

@endpush

@section('page-title')
    {{__('Task Board')}}
@endsection

@section('content')
    <div class="container-kanban" data-filter-list="card-list-body">
        <div class="container-fluid page-header d-flex justify-content-between align-items-start">
            <div class="col">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Tasks')}}</h3>
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-round" data-params="project_id={{$project_id}}" data-remote="true" data-type="text">
                            <i class="material-icons">add</i>
                        </a>
                    </div>
                    <div class="filter-container col-auto">
                        <div class="filter-controls">
                            <div>{{__('Sort')}}:</div>
                        </div>
                        <div class="filter-controls">
                            <a class="order" href="#" data-sort="order">{{__('Order')}}</a>
                            <a class="order" href="#" data-sort="priority">{{__('Priority')}}</a>
                            <a class="order" href="#" data-sort="due_date">{{__('Date')}}</a>
                        </div>
                        <div class="filter-tags">
                            <div>{{__('Tag')}}:</div>
                        </div>
                        <div class="filter-tags">
                            <div class="tag filter" data-filter="mine">{{__('My Tasks')}}</div>
                            <div class="tag filter" data-filter="all">{{__('All Tasks')}}</div>
                        </div>
                    </div>
                    <form class="col-md-auto">
                        <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                            </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="{{__("Filter tasks")}}" aria-label="{{__("Filter tasks")}}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="kanban-board container-fluid filter-list paginate-container">
            <div class="w-100 row justify-content-center pt-3">
                @include('partials.spinner')
            </div>
        </div>
    </div>
@endsection

@php clock()->endEvent('tasks.page'); @endphp
