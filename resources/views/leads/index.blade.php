@php
    use Carbon\Carbon;
@endphp

@foreach($stages as $stage)

    @if(\Auth::user()->type == 'company')
        @php($leads = $stage->leads)
    @else
        @php($leads = $stage->user_leads())
    @endif

    <div class="card-list">
        <div class="card-list-head">
        <h6>{{$stage->name}} ({{ count($leads) }})</h6>
        <div class="dropdown">
            <button class="btn-options" type="button" id="cardlist-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">more_vert</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="#">Rename</a>
            <a class="dropdown-item text-danger" href="#">Archive</a>
            </div>
        </div>
        </div>
        <div class="card-list-body">

        @foreach($leads as $lead)

            <div class="card card-task mb-1">
                <div class="container row align-items-center" style="min-height: 77px;">
                    <div class="pl-2 position-absolute">
                    </div>
                    <div class="card-body p-2">
                        <div class="card-title col-xs-12 col-sm-4">
                            <a href="#">
                            <h6 data-filter-by="text">{{$lead->name}}</h6>
                            </a>
                            <p>
                                <span class="text-small">{{__('Updated')}} {{ Carbon::parse($lead->updated_at)->diffForHumans() }}</span>
                            </p>
        
                        </div>
                        <div class="card-title col-xs-12 col-sm-2">
                            <span class="text-small" data-filter-by="text">
                                {{ \Auth::user()->priceFormat($lead->price) }}
                            </span>
                        </div>
                        <div class="card-title col-xs-12 col-sm-2">
                            <div class="container row align-items-center">
                                <span data-filter-by="text" class="badge badge-secondary mr-2">
                                    {{$lead->user()->name}}
                                </span>
                            </div>
                        </div>
                        <div class="card-meta col">
                            <div class="d-flex align-items-center justify-content-end">
                                <span data-filter-by="text" title="{{ $lead->notes }}" class="text-small text-truncate" style="max-width: 150px;">{{ $lead->notes }}</span>
                            </div>
                        </div>
                        <div class="dropdown card-options">
                            <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </button>

                            @if(Gate::check('edit lead') || Gate::check('delete lead'))
                            <div class="dropdown-menu dropdown-menu-right">
                                @can('edit lead')
                                <a class="dropdown-item" href="#" data-url="{{ route('leads.edit',$lead->id) }}" data-ajax-popup="true" data-title="{{__('Edit Lead')}}">
                                    <span>{{__('Edit')}}</span>
                                </a>
                                @endcan
                                <div class="dropdown-divider"></div>
                                @can('delete lead')
                                    <a class="dropdown-item text-danger" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$lead->id}}').submit();">
                                        <span>{{'Delete'}}</span>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['leads.destroy', $lead->id],'id'=>'delete-form-'.$lead->id]) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @endforeach

        </div>
    </div>
@endforeach
