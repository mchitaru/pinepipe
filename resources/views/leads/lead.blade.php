<div class="card card-task">
    <div class="container row align-items-center">
        <div class="pl-2 position-absolute">
        </div>
        <div class="card-body">
            <div class="card-title col-xs-12 col-sm-3">
                @if(Gate::check('viewAny', 'App\Lead'))
                <a href="{{ route('leads.show',$lead->id) }}">
                    <h6 data-filter-by="text">{{$lead->name}}</h6>
                </a>
                @else
                    <h6 data-filter-by="text">{{$lead->name}}</h6>
                @endif
                <p>
                    {!!\Helpers::showDateForHumans($lead->updated_at, __('Updated'))!!}
                </p>

            </div>
            <div class="card-title col-xs-12 col-sm-2">
                <span class="row text-small" data-filter-by="text">
                    {{ \Auth::user()->priceFormat($lead->price) }}
                </span>
                <div class="row">
                    <span class="badge badge-{{Helpers::getProgressColor($lead->progress)}}"> {{ $lead->stage->name }}</span>
                </div>
            </div>
            <div class="card-title col-xs-12 col-sm-3">
                <div class="d-flex align-items-center">
                    @if($lead->client)
                        <i class="material-icons mr-1">business</i>
                        @if(Gate::check('viewAny', 'App\Client'))
                        <a class title='{{__('Client')}}' href="{{ route('clients.show',$lead->client->id) }}">
                            {{$lead->client->name}}
                        </a>
                        @else
                            {{$lead->client->name}}
                        @endif
                    @endif
                </div>
            </div>
            <div class="card-meta col-1 float-right">
                <div class="container row align-items-center">
                    <a href="#"  title="{{$lead->user->name}}">
                        {!!Helpers::buildUserAvatar($lead->user)!!}
                    </a>
                </div>
            </div>
            @can('update', $lead)
            <div class="dropdown card-options float-right">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    @can('update', $lead)
                    <a class="dropdown-item" href="{{ route('leads.edit',$lead->id) }}" data-remote="true" data-type="text">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @if(!$lead->archived)
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('leads.update', $lead->id) }}" data-method="PATCH" data-remote="true" data-type="text">
                            {{__('Archive')}}
                        </a>
                    @else
                        <div class="dropdown-divider"></div>
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
        </div>
    </div>
</div>
