@php clock()->startEvent('contacts.index', "Display contacts"); @endphp

@foreach($contacts as $contact)
@can('view', $contact)
<div class="card card-task">
    <div class="container row align-items-center">
        <div class="pl-2 position-absolute">
        </div>
        <div class="card-body">
            <div class="card-title col-xs-12 col-sm-3">
                @if(Gate::check('update', $contact))
                <a href="{{ route('contacts.edit', $contact->id) }}" data-remote="true" data-type="text">
                    <h6 data-filter-by="text">{{$contact->name}}</h6>
                </a>
                @else
                    <h6 data-filter-by="text">{{$contact->name}}</h6>
                @endif
            </div>
            <div class="card-title col-xs-12 col-sm-5">
                <div class="container row align-items-center">
                    <span class="text-small">
                        <i class="material-icons">email</i>
                    </span>
                    @if($contact->email)
                        <a href="mailto:{{$contact->email}}">
                            <span data-filter-by="text" class="text-small">{{$contact->email}}</span>
                        </a>
                    @else
                        <span data-filter-by="text" class="text-small">---</span>
                    @endif
                </div>
                <div class="container row align-items-center">
                    <i class="material-icons">phone</i>
                    <span data-filter-by="text" class="text-small">{{$contact->phone?$contact->phone:'---'}}</span>
                </div>
            </div>
            <div class="card-title col">
                <div class="row align-items-center">
                    @if($contact->client)
                        <i class="material-icons mr-1">business</i>
                        @if(Gate::check('viewAny', 'App\Client'))
                        <a class  title='{{__('Client')}}' href="{{ route('clients.show',$contact->client->id) }}">
                            {{$contact->client->name}}
                        </a>
                        @else
                            {{$contact->client->name}}
                        @endif
                    @endif
                </div>
                <div class="row align-items-center">
                    @if(!$contact->tags->isEmpty())
                        <i class="material-icons">label</i>
                        @foreach($contact->tags as $tag)
                            <span class="badge badge-light" data-filter-by="text"> {{ $tag->name }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
            @can('update', $contact)
            <div class="dropdown card-options">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    @can('update', $contact)
                    <a class="dropdown-item" href="{{ route('contacts.edit', $contact->id) }}" data-remote="true" data-type="text">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @endcan
                    @can('delete', $contact)
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('contacts.destroy', $contact->id) }}" data-method="delete" data-remote="true" data-type="text">
                            <span>{{__('Delete')}}</span>
                        </a>
                    @endcan
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endcan
@endforeach

@if(method_exists($contacts,'links'))
{{ $contacts->links() }}
@endif

@php clock()->endEvent('contacts.index'); @endphp
