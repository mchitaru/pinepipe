@php
use App\Http\Helpers;
@endphp

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="container-fluid">
        <div class="col">
            <div class="card">
            <div class="container row" style="min-height: 67px;">
                <div class="pl-2 pt-3 position-absolute">
                    <a href="#" data-toggle="tooltip" title={{$client->name}}>
                        {!!Helpers::buildAvatar($client, 60, 'rounded')!!}
                    </a>
                </div>
                <div class="card-body pl-5">
                    <div class="card-title m-0 col-xs-12 col-sm-3">
                        @can('edit client')
                        <a href="{{ route('clients.edit',$client->id) }}" data-remote="true" data-type="text">
                        @endcan
                            <h4 data-filter-by="text">{{$client->name}}</h4>
                        @can('edit client')
                        </a>
                        @endcan
                    </div>
                    <div class="card-title m-0 col-xs-12 col-sm-5">
                        <div class="container row align-items-center">
                            <i class="material-icons">email</i>
                            <a href="mailto:kenny.tran@example.com">
                                <span data-filter-by="text" class="text-small">
                                    {{$client->email}}
                                </span>
                            </a>
                        </div>
                    </div>
                    {{-- <div class="card-meta col-2">
                        <div class="d-flex align-items-center justify-content-end">
                            <span class="badge badge-secondary mr-2">
                                <i class="material-icons" title="Projects">folder</i>
                                {{$client->clientProjects()->count()}}
                            </span>
                            <span class="badge badge-secondary mr-2">
                                <i class="material-icons" title="Leads">phone</i>
                                {{$client->clientLeads()->count()}}
                            </span>
                        </div>
                    </div> --}}
                    <div class="dropdown card-options">
                        @if($client->is_active)
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
    </div>        
</div>        
</div>
</div>
