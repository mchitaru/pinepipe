@foreach($clients as $client)
<div class="card card-task mb-1">
    <div class="container row align-items-center" style="min-height: 67px;">
        <div class="pl-2 position-absolute">
            <a href="#" data-toggle="tooltip" title={{$client->name}}>
                <img alt="{{$client->name}}" {!! empty($client->avatar) ? "avatar='".$client->name."'" : "" !!} class="avatar" src="{{asset(Storage::url("avatar/".$client->avatar))}}" data-filter-by="alt"/>
            </a>
        </div>
        <div class="card-body p-2 pl-5">
            <div class="card-title col-xs-12 col-sm-3">
                <a href="{{ route('clients.show',$client->id) }}">
                    <h6 data-filter-by="text">{{$client->name}}</h6>
                </a>
                @if(array_key_exists($client->name, $contacts_count))
                    <span class="text-small">{{$contacts_count[$client->name]}} contact(s)</span>
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
                    <span class="badge badge-secondary mr-2">
                        <i class="material-icons" title="Projects">folder</i>
                        {{$client->clientProjects()->count()}}
                    </span>
                    <span class="badge badge-secondary mr-2">
                        <i class="material-icons" title="Leads">phone</i>
                        {{$client->clientLeads()->count()}}
                    </span>
                </div>
            </div>
            <div class="dropdown card-options">
                @if($client->is_active)
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    @can('edit client')
                    <a class="dropdown-item" href="#" data-url="{{ route('clients.edit',$client->id) }}" data-ajax-popup="true" data-title="{{__('Update Client')}}">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    @can('delete client')
                        <a class="dropdown-item text-danger" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$client['id']}}').submit();">
                            <span>{{'Delete'}}</span>
                        </a>
                        {!! Form::open(['method' => 'DELETE', 'route' => ['clients.destroy', $client['id']],'id'=>'delete-form-'.$client['id']]) !!}
                        {!! Form::close() !!}
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
