<div class="mb-4">
    <h6>{{__('Subscription Plans')}}</h6>
    <div class="row">
        @foreach($plans as $key=>$plan)
        <div class="col-lg-6">
            <div class="card text-center" style="min-height: 270px;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mb-4">
                            <h6 class="text-center">
                                {{$plan->name}}
                            </h6>

                            <h4 class="mb-2 font-weight-bold">{{str_replace('.00','',Auth::user()->priceFormat($plan->duration?$plan->price/$plan->duration:$plan->price))}}
                                <span class="text-small">{{$plan->price?'/month':''}}</span>
                            </h4>                                    
                            <ul class="list-unstyled">
                                <li class="text-small">
                                    <b>{{!isset($plan->max_clients)?'Unlimited':$plan->max_clients}}</b> {{__('client(s)')}}
                                </li>
                                <li class="text-small">
                                    <b>{{!isset($plan->max_projects)?'Unlimited':$plan->max_projects}}</b> {{__('project(s)')}}
                                </li>
                                <li class="text-small">
                                    <b>{{!isset($plan->max_users)?'Unlimited':$plan->max_users}}</b> {{__('collaborator(s)')}}
                                </li>
                            </ul>
                            @if($key != 0 && $user_plan->id != $plan->id)
                                <a href="{{ route('subscriptions.create') }}" class="btn btn-primary" data-params="plan_id={{$plan->id}}" data-remote="true" data-type="text">
                                    {{__('Upgrade')}}
                                </a>         
                            @elseif($user_plan->id == $plan->id)
                                <a href="#" class="btn btn-success disabled">
                                    {{__('Active')}}
                                </a>         
                            {{-- <span class="badge badge-primary">active</span> --}}
                            @endif   
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- @if($user->subscribed())
<div class="mb-4">
    <h6>{{__('Active Subscription')}}</h6>
    <div class="row">
        <div class="card">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto">
                    <span>{{__('Type:')}} </span>
                    <span class="badge badge-primary">{{$user->subscription()->braintree_plan}}</span>
                </div>
                <div class="col-auto">
                    <span>{{__('Status:')}} </span>
                    @if($user->subscription()->onGracePeriod())
                        <span class="badge badge-secondary">{{__('Grace Period')}}</span>
                    @elseif($user->subscription()->onTrial())
                        <span class="badge badge-secondary">{{__('Trial Period')}}</span>
                    @else
                        <span class="badge badge-secondary">{{__('Active')}}</span>
                    @endif
                </div>                
                <div class="col-auto">
                    <a href="{{ route('subscriptions.destroy', $user->subscription()->id) }}" class="btn btn-primary" data-method="delete" data-remote="true" data-type="text">
                        {{__('Cancel')}}
                    </a>         
                </div>
              </div>
            </div>
        </div>    
    </div>
</div>
@endif     --}}

{{-- <div class="mb-4">
    <h6>{{__('Payment Method')}}</h6>
    <div class="card">
        <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
            <div class="custom-control custom-radio d-inline-block">
                <input type="radio" id="method-radio-1" name="payment-method" class="custom-control-input" checked>
                <label class="custom-control-label" for="method-radio-1"></label>
            </div>
            </div>
            <div class="col-auto">
            <img alt="Image" src="{{ asset('assets/img/logo-payment-visa.svg') }}" class="avatar rounded-0" />
            </div>
            <div class="col d-flex align-items-center">
            <span>•••• •••• •••• 8372</span>
            <small class="ml-2">Exp: 06/21</small>
            </div>
            <div class="col-auto">
            <button class="btn btn-sm btn-danger disabled">
                {{__('Remove Card')}}
            </button>
            </div>
        </div>
        </div>
    </div>
</div> --}}
