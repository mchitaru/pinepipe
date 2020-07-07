@php clock()->startEvent('leads.board', "Display lead board"); @endphp

@php
use Carbon\Carbon;
@endphp

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
                                <span>{{__('Delete')}}</span>
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
                            <a class title='{{__('Client')}}' href="{{ route('clients.show',$lead->client->id) }}">
                                <p><span data-filter-by="text" class="text-small">{{ $lead->client->name }}</span></p>
                            </a>
                            @else
                                <p><span data-filter-by="text" class="text-small">{{ $lead->client->name }}</span></p>
                            @endif
                        @endif
                    </div>
                    <div class="card-title">
                        <span data-filter-by="text" class="text-small price" data-id={{$lead->price}}>
                            {{ \Auth::user()->priceFormat($lead->price) }}
                        </span>
                        @if(!empty($lead->user))
                        <div data-filter-by="text" class="float-right">
                            <a href="#"  title="{{$lead->user->name}}">
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
        <a href="{{ route('stages.create') }}" class="btn btn-link btn-sm text-small" data-params="class=App\Lead&order={{$stages->last()?($stages->last()->order + 1):0}}" data-remote="true" data-type="text">
            {{__('Add Stage')}}
        </a>
    </div>
</div>
@endcan

@php clock()->endEvent('leads.board'); @endphp
