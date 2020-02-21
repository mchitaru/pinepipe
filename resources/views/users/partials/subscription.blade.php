<div class="mb-4">
    <h6>{{__('Subscription')}}</h6>
    <div class="card text-center">
        <div class="card-body">
            <div class="row">
                @foreach($plans as $key=>$plan)
                <div class="col-6 mb-4">            
                    <div class="mb-4">
                        <h6>
                            {{$plan->name}}
                            @if($user_plan->id == $plan->id)
                                <span class="badge badge-primary">active</span>
                            @endif
                        </h6>

                        <h4 class="mb-2 font-weight-bold">{{str_replace('.00','',Auth::user()->priceFormat($plan->duration?$plan->price/$plan->duration:$plan->price))}}
                            <span class="text-small">{{$plan->price?'/month':''}}</span>
                        </h4>                                    
                    </div>
                    <ul class="list-unstyled">
                        <li class="text-small">
                            <b>{{$plan->max_clients?$plan->max_clients:'Unlimited'}}</b> {{__('client(s)')}}
                        </li>
                        <li class="text-small">
                            <b>{{$plan->max_projects?$plan->max_projects:'Unlimited'}}</b> {{__('project(s)')}}
                        </li>
                        <li class="text-small">
                            <b>{{$plan->max_users?$plan->max_users:'Unlimited'}}</b> {{__('user(s)')}}
                        </li>
                    </ul>
                    @if($key != 0 && $user_plan->id != $plan->id)
                        <a href="{{ route('subscriptions.create') }}" class="btn btn-primary" data-params="plan_id={{$plan->id}}" data-remote="true" data-type="text">
                            {{__('Upgrade')}}
                        </a>         
                    @endif   
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

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
