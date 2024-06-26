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
                    <h6 class="mb-1">{{$stage->name}}</h6>
                    <span class="small count">({{ $stage->lead_count }})</span>
                </div>
                <span class="total" data-id={{$stage->lead_total}}>{{ \Auth::user()->priceFormat($stage->lead_total) }}</span>
                @can('update', $stage)
                <div class="dropdown">
                    <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        @can('update', $stage)
                            <a class="dropdown-item" href="{{ route('stages.edit',$stage->id) }}" data-remote="true" data-type="text">
                                <span>{{__('Edit')}}</span>
                            </a>
                        @endcan
                        @can('delete', $stage)
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="{{ route('stages.destroy',$stage->id) }}" data-method="delete" data-remote="true" data-type="text">
                                <span>{{__('Delete')}}</span>
                            </a>
                        @endcan
                    </div>
                </div>
                @endcan
            </div>
        </div>
        <div class="card-list-body" data-id={{$stage->id}} >
            @foreach($stage->leads as $lead)
            @can('view', $lead)
            <div class="card card-kanban" data-id={{$lead->id}}>
                <div class="card-body p-2">
                    @can('update', $lead)
                        <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="kanban-dropdown-button-14" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @can('update', $lead)
                                <a class="dropdown-item" href="{{ route('leads.edit',$lead->id) }}" data-remote="true" data-type="text">
                                    <span>{{__('Edit')}}</span>
                                </a>
                                @endcan
                                @can('update', $lead)
                                    <div class="dropdown-divider"></div>
                                    @if(!$lead->archived)
                                        <a class="dropdown-item text-danger" href="{{ route('leads.update', $lead->id) }}" data-method="PATCH" data-remote="true" data-type="text">
                                            {{__('Archive')}}
                                        </a>
                                    @else
                                        <a href="{{ route('leads.update', $lead->id) }}" class="dropdown-item text-danger" data-params="archived=0" data-method="PATCH" data-remote="true" data-type="text">
                                            {{__('Restore')}}
                                        </a>
                                    @endif
                                @endcan
                                @can('delete', $lead)
                                    <a class="dropdown-item text-danger" href="{{ route('leads.destroy', $lead->id) }}" data-method="delete" data-remote="true" data-type="text">
                                        <span>{{__('Delete')}}</span>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endcan
                    <div class="card-title m-xl-0">
                        @if(Gate::check('viewAny', 'App\Lead'))
                        <a href="{{ route('leads.show',$lead->id) }}" title="{{$lead->name}}">
                            <h6 data-filter-by="text" class="text-truncate">{{$lead->name}}</h6>
                        </a>
                        @else
                            <h6 data-filter-by="text" class="text-truncate">{{$lead->name}}</h6>
                        @endif

                        @if($lead->client)
                            @if(Gate::check('viewAny', 'App\Client'))
                            <a class title='{{__('Client')}}' href="{{ route('clients.show',$lead->client->id) }}">
                                <p><span data-filter-by="text" class="text-small">{{ $lead->client->name }}</span></p>
                            </a>
                            @else
                                <p><span data-filter-by="text" class="text-small">{{ $lead->client->name }}</span></p>
                            @endif
                        @endif
                    </div>
                    <div class="card-title justify-content-between align-items-center d-none d-xl-flex">
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
            @endcan
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

@can('create', 'App\Stage')
<div class="kanban-col">
    <div class="card-list">
        <a href="{{ route('stages.create') }}" class="btn btn-link btn-sm text-small" data-params="class=App\Lead&order={{$stages->last()?($stages->last()->order + 1):0}}" data-remote="true" data-type="text">
            {{__('Add Stage')}}
        </a>
    </div>
</div>
@endcan

@php clock()->endEvent('leads.board'); @endphp
