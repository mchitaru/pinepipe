@php clock()->startEvent('contacts.index', "Display contacts"); @endphp

@foreach($contacts as $contact)
<div class="card card-task">
    <div class="container row align-items-center" style="min-height: 77px;">
        <div class="pl-2 position-absolute">
        </div>
        <div class="card-body p-2">
            <div class="card-title col-xs-12 col-sm-3">
                @can('edit contact')
                <a href="{{ route('contacts.edit', $contact->id) }}" data-remote="true" data-type="text">
                @endcan
                    <h6 data-filter-by="text">{{$contact->name}}</h6>
                @can('edit contact')
                </a>
                @endcan
            </div>
            <div class="card-title col-xs-12 col-sm-5">
                <div class="container row align-items-center">
                    <span class="text-small">
                        <i class="material-icons">email</i>
                    </span>
                    <a href="mailto:kenny.tran@example.com">
                        <span data-filter-by="text" class="text-small">{{$contact->email}}</span>
                    </a>
                </div>
                <div class="container row align-items-center">
                    <i class="material-icons">phone</i>
                    <span data-filter-by="text" class="text-small">{{$contact->phone}}</span>
                </div>
            </div>
            <div class="card-meta col">
                @if($contact->client)
                <div class="d-flex align-items-center justify-content-end">
                    @can('show client')
                    <a class data-toggle="tooltip" title='{{__('Client')}}' href="{{ $contact->client->enabled?route('clients.show',$contact->client->id):'#' }}">
                    @endcan
                        {{$contact->client->name}}
                    @can('show client')
                    </a>
                    @endcan        
                </div>
                @endif
            </div>
            <div class="dropdown card-options">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    @can('edit contact')
                    <a class="dropdown-item" href="{{ route('contacts.edit', $contact->id) }}" data-remote="true" data-type="text">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    @can('delete contact')
                        <a class="dropdown-item text-danger" href="{{ route('contacts.destroy', $contact->id) }}" data-method="delete" data-remote="true" data-type="text">
                            <span>{{'Delete'}}</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

{{ $contacts->links() }}

@php clock()->endEvent('contacts.index'); @endphp
