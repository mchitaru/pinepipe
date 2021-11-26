@php
    $monthly = ($user_plan->duration == 1);
@endphp

<div class="mb-4">
    @if(!$user->getCompany()->subscribed())
        <div class="alert alert-warning text-small" role="alert">
            <span>{{__('You have limited functionality on the Free plan. Please choose a subscription and start your 14 days FREE trial today!')}}</span>
        </div>
    @else
        <div class="alert alert-warning text-small" role="alert">
            <span>{!!__('You are on the <b>:plan (:period)</b> subscription', ['plan' => $user_plan->name, 'period' => ($user_plan->duration == null)?__('lifetime'):($monthly?__('monthly'):__('yearly'))])!!}</span>
        </div>
    @endif
    <div class="row pt-3">
        <div class="col-lg-2">
        </div>
        <div class="col-lg-8 col-12 d-flex align-items-center justify-content-center">
            <label for="toggle" class="switch-label text-uppercase px-3 {{$monthly ? 'font-weight-bold':'font-weight-light'}}">{{__('MONTHLY')}}</label>
            <input type="checkbox" id="toggle" class="checkbox" {{$monthly ? '':'checked'}}/>
            <label for="toggle" class="switch"></label>
            <label for="toggle" class="switch-label text-uppercase px-3 {{$monthly ? 'font-weight-light':'font-weight-bold'}}">{{__('YEARLY')}}</label>
        </div>
    </div>
    <div class="row pt-2">
    <div class="col-lg-2">
    </div>
    <div class="col-lg-8 col-12">
        @foreach($plans as $key=>$plan)
        @if(($plan->active && $plan->name != $user_plan->name) || $user_plan->id == $plan->id)
        <div class="col-lg-12">
            <div class="card {{$user_plan->id == $plan->id?'bg-warning':''}} text-center {{ $plan->duration? "card-subscription":""}} {{ ($monthly && $plan->duration == 12) || (!$monthly && $plan->duration == 1) ? "d-none":""}}" style="min-height: 300px">
                <div class="card-body">
                    <div class="row">
                        <div class="col mb-4">
                            <h4 class="text-center">
                                {{$plan->name}}
                            </h4>
                            @if($user_plan->id == $plan->id)
                                <span class="badge badge-primary">{{__('active')}}</span>
                            @endif
                            <br><br>
                            <span class="text-small">{{$plan->description}}</span>
                            <h1 class="mb-2 font-weight-bold">€{{str_replace('.00','',$plan->duration?\Helpers::ceil($plan->price/$plan->duration):$plan->price)}}
                                <span class="text-small">{{($plan->price && isset($plan->duration))?'/'.__('month'):''}}</span>
                            </h1>
                            <ul class="list-unstyled">
                                <li class="text-small">
                                    <b>{{!isset($plan->max_clients)?__('Unlimited'):$plan->max_clients}}</b> {{__('client(s)')}}
                                </li>
                                <li class="text-small">
                                    <b>{{!isset($plan->max_projects)?__('Unlimited'):$plan->max_projects}}</b> {{__('project(s)')}}
                                </li>
                                <li class="text-small">
                                    @if($user_plan->id == $plan->id && $user->subscription() && $user->subscription()->active())
                                        <b>{{!isset($user->subscription()->max_users)?__('Unlimited collaborators'):($user->subscription()->max_users==0?__('No collaborators'):$user->subscription()->max_users.' '.__('collaborator(s)'))}}</b>
                                    @else
                                        <b>{{!isset($plan->max_users)?__('Unlimited collaborators'):($plan->max_users==0?__('No collaborators'):$plan->max_users.' '.__('collaborator(s)'))}}</b>
                                    @endif
                                </li>
                            </ul>
                            @if($key != 0 )
                                @if($user_plan->id != $plan->id)
                                    <a href="#" data-override="{{$payLinks[$key]}}" data-theme="none" class="paddle_button btn btn-primary {{($user->subscription() && $user->subscription()->active())?'disabled':''}}">
                                        {{$plan->trial?__('Start free trial'):__('Activate')}}
                                    </a>
                                @elseif(!$plan->deal && $user_plan->id == $plan->id)
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
        @endif
        @endforeach
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
