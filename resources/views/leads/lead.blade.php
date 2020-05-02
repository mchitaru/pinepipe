<div class="card card-task mb-1">
    <div class="container row align-items-center" style="min-height: 77px;">
        <div class="pl-2 position-absolute">
        </div>
        <div class="card-body p-2">
            <div class="card-title col-xs-12 col-sm-3">
                @if(Gate::check('view lead'))
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
                    <span class="badge badge-secondary"> {{ $lead->stage->name }}</span>
                </div>
            </div>
            <div class="card-title col-xs-12 col-sm-3">
                <div class="d-flex align-items-center">
                    @if($lead->client)
                        <i class="material-icons mr-1">apartment</i>
                        @if(Gate::check('view client'))
                        <a class data-toggle="tooltip" title='{{__('Client')}}' href="{{ route('clients.show',$lead->client->id) }}">
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
