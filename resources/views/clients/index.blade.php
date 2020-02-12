@php
use App\Http\Helpers;
@endphp

@php clock()->startEvent('clients.index', "Display clients"); @endphp

@foreach($clients as $client)
<div class="card card-task">
    <div class="container row align-items-center" style="min-height: 67px;">
        <div class="pl-2 position-absolute">
            <a href="#" data-toggle="tooltip" title={{$client->name}}>
                {!!Helpers::buildAvatar($client)!!}
            </a>
        </div>
        <div class="card-body p-2 pl-5">
            <div class="card-title col-xs-12 col-sm-4">
                @can('show client')
                <a href="{{ $client->enabled?route('clients.show',$client->id):'#' }}">
                @endcan
                    <h6 data-filter-by="text">{{$client->name}}</h6>
                @can('show client')
                </a>
                @endcan
                @if(!$client->clientContacts->isEmpty())
                    <span class="text-small">{{$client->clientContacts->count()}} {{__('contact(s)')}}</span>
                @endif
            </div>
            <div class="card-title col-xs-12 col-sm-5">
                <div class="container row align-items-center">
                    <i class="material-icons">email</i>
                    <a href="mailto:kenny.tran@example.com">
                        <span data-filter-by="text" class="text-small">
                            {{$client->email}}
                        </span>
                    </a>
                </div>
            </div>
            <div class="card-meta col-2">
                <div class="d-flex align-items-center justify-content-end">
                    <span class="badge badge-light mr-2">
                        <i class="material-icons" data-toggle="tooltip" title="Projects">folder</i>
                        {{$client->clientProjects->count()}}
                    </span>
                    <span class="badge badge-light mr-2">
                        <i class="material-icons" data-toggle="tooltip" title="Leads">phone</i>
                        {{$client->clientLeads->count()}}
                    </span>
                </div>
            </div>
            <div class="dropdown card-options">
                @if($client->enabled)
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
                @else
                    <i class="material-icons">lock</i>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
{{ $clients->fragment('clients')->links() }}

@php clock()->endEvent('clients.index'); @endphp
