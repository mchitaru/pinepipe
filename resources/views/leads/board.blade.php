@extends('layouts.app')

@php clock()->startEvent('leads.board', "Display lead board"); @endphp

@php
use Carbon\Carbon;
@endphp

@push('stylesheets')
@endpush

@push('scripts')
<script type="text/javascript" src="{{ asset('assets/js/draggable.bundle.min.js') }}"></script>

<script>

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

                    toastrs('Stage order succesfully updated.', 'success');
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

        var lead_id = evt.newContainer.children[evt.newIndex].attributes['data-id'].value;
        var lead_value = parseInt($(evt.newContainer.children[evt.newIndex]).find('.price').attr('data-id'));

        var old_stage_id = evt.oldContainer.attributes['data-id'].value;
        var new_stage_id = evt.newContainer.attributes['data-id'].value;

        var total_old = parseInt($(evt.oldContainer).prev().find('.total').attr('data-id'));
        var total_new = parseInt($(evt.newContainer).prev().find('.total').attr('data-id'));

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
                    toastrs('Lead succesfully updated.', 'success');
                }
            },
            error: function (data) {
                /* console.log('error'); */
            }
        });
    });

</script>

@endpush

@section('page-title')
    {{__('Lead Board')}}
@endsection

@section('content')

    <div class="container-kanban" data-filter-list="card-list-body">
        <div class="container-fluid page-header d-flex justify-content-between align-items-start">
            <div class="col">
                <div class="row content-list-head">
                    <div class="col-auto">
                        <h3>{{__('Leads')}}</h3>
                        @can('create lead')
                        <a href="{{ route('leads.create') }}" class="btn btn-round" data-remote="true" data-type="text">
                            <i class="material-icons">add</i>
                        </a>
                        @endcan
                    </div>
                    <div class="col-md-auto">
                        <form>
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
                </div>
            </div>
        </div>
        <div class="kanban-board container-fluid">

            @foreach($stages as $stage)

            @php $stage->computeStatistics() @endphp

            <div class="kanban-col" data-id={{$stage->id}}>
                <div class="card-list">
                    <div class="card-list-header">
                        <div class="col">
                            <div class="row">
                                <h6>{{$stage->name}}</h6>
                                <span class="small count">({{ $stage->leads->count() }})</span>
                            </div>
                            <span class="total" data-id={{$stage->total_amount}}>{{ \Auth::user()->priceFormat($stage->total_amount) }}</span>
                            <div class="dropdown">
                                <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @can('edit lead stage')
                                        <a class="dropdown-item" href="{{ route('stages.edit',$stage->id) }}" data-remote="true" data-type="text">
                                            <span>{{__('Edit')}}</span>
                                        </a>
                                    @endcan
                                    <div class="dropdown-divider"></div>
                                    @can('delete lead stage')
                                        <a class="dropdown-item text-danger" href="{{ route('stages.destroy',$stage->id) }}" data-method="delete" data-remote="true" data-type="text">
                                            <span>{{__('Delete')}}</span>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-list-body" data-id={{$stage->id}}>

                        @foreach($stage->leads as $lead)

                        <div class="card card-kanban" data-id={{$lead->id}}>

                        <div class="card-body p-2">
                            <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="kanban-dropdown-button-14" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @can('edit lead')
                                <a class="dropdown-item" href="{{ route('leads.edit',$lead->id) }}" data-remote="true" data-type="text">
                                    <span>{{__('Edit')}}</span>
                                </a>
                                @endcan
                                <div class="dropdown-divider"></div>
                                @can('delete lead')
                                    <a class="dropdown-item text-danger" href="{{ route('leads.destroy', $lead->id) }}" data-method="delete" data-remote="true" data-type="text">
                                        <span>{{'Delete'}}</span>
                                    </a>
                                @endcan
                            </div>
                            </div>
                            <div class="card-title">
                                @if(Gate::check('view lead'))
                                <a href="{{ route('leads.show',$lead->id) }}">
                                    <h6 data-filter-by="text" class="text-truncate">{{$lead->name}}</h6>
                                </a>
                                @else
                                    <h6 data-filter-by="text" class="text-truncate">{{$lead->name}}</h6>
                                @endif

                                @if($lead->client)
                                    @if(Gate::check('view client'))
                                    <a class data-toggle="tooltip" title='{{__('Client')}}' href="{{ route('clients.show',$lead->client->id) }}">
                                        <p><span class="text-small">{{ $lead->client->name }}</span></p>
                                    </a>
                                    @else
                                        <p><span class="text-small">{{ $lead->client->name }}</span></p>
                                    @endif
                                @endif
                            </div>

                            <div class="card-title">
                                <span class="text-small price" data-id={{$lead->price}}>
                                    {{ \Auth::user()->priceFormat($lead->price) }}
                                </span>
                                @if(!empty($lead->user))
                                <div class="float-right">
                                    <a href="#" data-toggle="tooltip" title="{{$lead->user->name}}">
                                        {!!Helpers::buildUserAvatar($lead->user)!!}
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        </div>

                        @endforeach

                    </div>
                    <div class="card-list-footer">
                        <a href="{{ route('leads.create') }}" class="btn btn-link btn-sm text-small" data-params="stage_id={{$stage->id}}" data-remote="true" data-type="text">
                            {{__('Add lead')}}
                        </a>
                    </div>
                </div>
            </div>

            @endforeach

            @can('create lead stage')
            <div class="kanban-col">
                <div class="card-list">
                    <a href="{{ route('stages.create') }}" class="btn btn-link btn-sm text-small" data-params="class=App\Lead&order={{$stages->last()->order + 1}}" data-remote="true" data-type="text">
                        {{__('Add Stage')}}
                    </a>
                </div>
            </div>
            @endcan
        </div>
    </div>
@endsection

@php clock()->endEvent('leads.board'); @endphp
