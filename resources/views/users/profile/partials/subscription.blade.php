<div class="mb-4">
    <h6>{{__('Subscription')}}</h6>
    <div class="row">
        @foreach($plans as $key=>$plan)
        <div class="col-lg-6">
            <div class="card {{$plan->deal?'bg-warning':''}} text-center" style="min-height: 270px;">
                <div class="card-body">
                    <div class="row">
                        <div class="col mb-4">
                            <h5 class="text-center">
                                {{$plan->name}}

                                @if($user_plan->id == $plan->id)
                                    <span class="badge badge-primary">{{__('active')}}</span>
                                @endif
                            </h5>

                            <h4 class="mb-2 font-weight-bold">€{{str_replace('.00','',$plan->duration?$plan->price/$plan->duration:$plan->price)}}
                                <span class="text-small">{{($plan->price && isset($plan->duration))?'/'.__('month'):''}}</span>
                            </h4>
                            <ul class="list-unstyled">
                                <li class="text-small">
                                    <b>{{!isset($plan->max_clients)?__('Unlimited'):$plan->max_clients}}</b> {{__('client(s)')}}
                                </li>
                                <li class="text-small">
                                    <b>{{!isset($plan->max_projects)?__('Unlimited'):$plan->max_projects}}</b> {{__('project(s)')}}
                                </li>
                                <li class="text-small">
                                    <b>{{!isset($plan->max_users)?__('Unlimited collaborators'):($plan->max_users==0?__('No collaborators'):$plan->max_users.' '.__('collaborator(s)'))}}</b>
                                </li>
                            </ul>
                            @if($key != 0 )
                                @if($user_plan->id != $plan->id)
                                    <a href="{{ route('subscriptions.create', $plan->id) }}" class="btn btn-primary {{($user->subscription() && $user->subscription()->active())?'disabled':''}}">
                                        {{__('Start Trial')}}
                                    </a>
                                @elseif($user_plan->id == $plan->id)
                                    <a href="{{ route('subscriptions.destroy', $user->subscription()->id) }}" class="btn btn-danger {{(!$user->subscription()->active() || Session::has('canceled'))?'disabled':''}}" data-method="delete" data-remote="true" data-type="text">
                                        {{($user->subscription()->active() && !Session::has('canceled'))?__('Cancel'):__('Canceled')}}
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
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
