@foreach($contacts as $contact)
<div class="card card-task mb-1">
    <div class="container row align-items-center" style="min-height: 77px;">
        <div class="pl-2 position-absolute">
        </div>
        <div class="card-body p-2">
            <div class="card-title col-xs-12 col-sm-3">
                <a href="#">
                <h6 data-filter-by="text">{{$contact->name}}</h6>
                </a>
            </div>
            <div class="card-title col-xs-12 col-sm-5">
                <div class="container row align-items-center">
                    <span class="text-small">
                        <i class="material-icons">email</i>
                    </span>
                    <a href="mailto:kenny.tran@example.com">
                        <h6 data-filter-by="text">{{$contact->email}}</h6>
                    </a>
                </div>
                <div class="container row align-items-center">
                    <i class="material-icons">phone</i>
                    <span data-filter-by="text" class="text-small">{{$contact->phone}}</span>
                </div>
            </div>
            <div class="card-meta col">
                <div class="d-flex align-items-center justify-content-end">
                    <span data-filter-by="text" class="badge badge-secondary mr-2">
                        {{$contact->company}}
                    </span>
                </div>
            </div>
            <div class="dropdown card-options">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    @can('edit client')
                    <a class="dropdown-item" href="#" data-url="{{ route('contacts.edit',$contact->id) }}" data-ajax-popup="true" data-title="{{__('Update Contact')}}">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    @can('delete client')
                        <a class="dropdown-item text-danger" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('contact-delete-form-{{$contact['id']}}').submit();">
                            <span>{{'Delete'}}</span>
                        </a>
                        {!! Form::open(['method' => 'DELETE', 'route' => ['contacts.destroy', $contact['id']],'id'=>'contact-delete-form-'.$contact['id']]) !!}
                        {!! Form::close() !!}
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
