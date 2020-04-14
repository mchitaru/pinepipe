@php clock()->startEvent('clients.index', "Display clients"); @endphp

@foreach($clients as $client)
<div class="card card-task">
    <div class="container row align-items-center" style="min-height: 67px;">
        <div class="pl-2 position-absolute">
            <a href="#" data-toggle="tooltip" title={{$client->name}}>
                {!!Helpers::buildClientAvatar($client)!!}
            </a>
        </div>
        <div class="card-body p-2 pl-5">
            <div class="card-title col-xs-12 col-sm-4">
                @if(Gate::check('show client'))
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
                    <a href="mailto:kenny.tran@example.com">
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
                        <i class="material-icons" data-toggle="tooltip" title="Projects">folder</i>
                        {{$client->projects->count()}}
                    </span>
                    <span class="badge badge-light mr-2">
                        <i class="material-icons" data-toggle="tooltip" title="Leads">phone</i>
                        {{$client->leads->count()}}
                    </span>
                </div>
            </div>
            <div class="dropdown card-options">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    @can('edit client')
                        <a class="dropdown-item" href="{{ route('clients.edit',$client->id) }}" data-remote="true" data-type="text">
                            <span>{{__('Edit')}}</span>
                        </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger disabled" href="#">{{__('Archive')}}</a>
                    @can('delete client')
                        <a class="dropdown-item text-danger" href="{{ route('clients.destroy', $client->id) }}" data-method="delete" data-remote="true" data-type="text">
                            <span>{{'Delete'}}</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@if(method_exists($clients,'links'))
{{ $clients->links() }}
@endif

@php clock()->endEvent('clients.index'); @endphp
