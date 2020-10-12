@php clock()->startEvent('clients.index', "Display clients"); @endphp

@foreach($clients as $client)
@can('view', $client)
<div class="card card-task">
    <div class="container row align-items-center">
        <div class="pl-2 position-absolute">
            <a href="#"  title={{$client->name}}>
                {!!Helpers::buildClientAvatar($client)!!}
            </a>
        </div>
        <div class="card-body pl-5">
            <div class="card-title col-xs-12 col-sm-4">
                @if(Gate::check('viewAny', 'App\Client'))
                <a href="{{ route('clients.show',$client->id) }}">
                    <h6 data-filter-by="text">{{$client->name}}</h6>
                </a>
                @else
                    <h6 data-filter-by="text">{{$client->name}}</h6>
                @endif

                @if(!$client->contacts->isEmpty())
                    <span class="text-small">{{$client->contacts->count()}} {{__('contact(s)')}}</span>
                @endif
            </div>
            <div class="card-title col-xs-12 col-sm-5">
                @if(!empty($client->email))
                <div class="container row align-items-center">
                    <i class="material-icons">email</i>
                    <a href="mailto:{{$client->email}}">
                        <span data-filter-by="text" class="text-small">
                            {{$client->email}}
                        </span>
                    </a>
                </div>
                @endif
            </div>
            <div class="card-meta col-2">
                <div class="d-flex align-items-center justify-content-end">
                    <span class="badge badge-light mr-2">
                        <i class="material-icons"  title="Projects">folder</i>
                        {{$client->projects->count()}}
                    </span>
                    <span class="badge badge-light mr-2">
                        <i class="material-icons"  title="Leads">phone</i>
                        {{$client->leads->count()}}
                    </span>
                </div>
            </div>
            <div class="dropdown card-options">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    @can('update', $client)
                        <a class="dropdown-item" href="{{ route('clients.edit',$client->id) }}" data-remote="true" data-type="text">
                            <span>{{__('Edit')}}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        @if(!$client->archived)
                            <a class="dropdown-item text-danger" href="{{ route('clients.update', $client->id) }}" data-method="PATCH" data-remote="true" data-type="text">
                                {{__('Archive')}}
                            </a>
                        @else
                            <a href="{{ route('clients.update', $client->id) }}" class="dropdown-item text-danger" data-params="archived=0" data-method="PATCH" data-remote="true" data-type="text">
                                {{__('Restore')}}
                            </a>
                        @endif
                    @endcan
                    @can('delete', $client)
                        <a class="dropdown-item text-danger" href="{{ route('clients.destroy', $client->id) }}" data-method="delete" data-remote="true" data-type="text">
                            <span>{{__('Delete')}}</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endforeach

@if(method_exists($clients,'links'))
{{ $clients->links() }}
@endif

@php clock()->endEvent('clients.index'); @endphp
