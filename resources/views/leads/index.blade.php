@php
use Carbon\Carbon;
@endphp

@php clock()->startEvent('leads.index', "Display leads"); @endphp

@foreach($leads as $lead)

    <div class="card card-task mb-1">
        <div class="container row align-items-center" style="min-height: 77px;">
            <div class="pl-2 position-absolute">
            </div>
            <div class="card-body p-2">
                <div class="card-title col-xs-12 col-sm-3">
                    @can('edit lead')
                    <a href="{{ route('leads.edit',$lead->id) }}" data-remote="true" data-type="text">
                    @endcan
                        <h6 data-filter-by="text">{{$lead->name}}</h6>
                    @can('edit lead')
                    </a>
                    @endcan
                    <p>
                        <span class="text-small">
                            {{__('Updated')}} {{ Carbon::parse($lead->updated_at)->diffForHumans() }}
                        </span>
                    </p>

                </div>
                <div class="card-title col-xs-12 col-sm-2">
                    <span class="row text-small" data-filter-by="text">
                        {{ \Auth::user()->priceFormat($lead->price) }}
                    </span>
                    <div class="row">
                        <span class="badge badge-secondary"> {{ $lead->stage->name }}</span>
                    </div>
                </div>
                <div class="card-title col-xs-12 col-sm-3">
                    <div class="d-flex align-items-center">
                        @if($lead->client)
                            @can('show client')
                            <a class data-toggle="tooltip" title='{{__('Client')}}' href="{{ $lead->client->enabled?route('clients.show',$lead->client->id):'#' }}">
                            @endcan
                                {{$lead->client->name}}
                            @can('show client')
                            </a>
                            @endcan        
                        @endif
                    </div>
                </div>
                @if(!empty($lead->notes))
                <div class="card-title col-xs-12 col-sm-1">
                    <div class="d-flex align-items-center">
                        <span data-filter-by="text" title="{{ $lead->notes }}" class="badge badge-secondary mr-2">
                            <i class="material-icons">note</i>
                        </span>
                    </div>
                </div>
                @endif
                <div class="card-meta col-1 float-right">
                    <div class="container row align-items-center">
                        <a href="#" data-toggle="tooltip" title="{{$lead->user->name}}">
                            {!!Helpers::buildUserAvatar($lead->user)!!}
                        </a>
                    </div>
                </div>
                <div class="dropdown card-options float-right">
                    <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </button>

                    @if(Gate::check('edit lead') || Gate::check('delete lead'))
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
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach

@if(method_exists($leads,'links'))
 {{ $leads->fragment('leads')->links() }}
@endif

@php clock()->endEvent('leads.index'); @endphp
