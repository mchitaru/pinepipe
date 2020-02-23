@extends('layouts.modal')

@section('form-start')
@endsection

@section('title')
    {{__('Secure Checkout')}}
@endsection

@push('scripts')

<script>
$(function() {

    var button = document.querySelector('#submit-button');

    braintree.dropin.create({
      authorization: "{{ Braintree_ClientToken::generate(["customerId" => \Auth::user()->braintree_id]) }}",
      container: '#dropin-container'
    }, function (createErr, instance) {

        button.addEventListener('click', function () {

            button.textContent = 'Processing...';
            button.setAttribute('disabled', 'true');

            instance.requestPaymentMethod(function (err, payload) {

            subscription = '{{$plan->id}}';

            $.post('{{ route('subscriptions.store') }}', {payload, subscription}, function (response) {
                if (response.success) {
                    console.log('Payment successfull!');

                    button.setAttribute('class', 'btn btn-success float-right');
                    button.textContent = 'Done!';
                    location.reload();

                } else {
                    console.log('Payment failed!');

                    button.removeAttribute('disabled', 'false');
                    button.setAttribute('class', 'btn btn-danger float-right');
                    button.textContent = 'Retry';
                }
            }, 'json');
            });
        });
    });

});
</script>

@endpush

@section('content')
<div class="tab-content">
<h6 class="mb-3">{{__('Your cart')}}</h6>
<div class="form-group row mb-3">
    <div class="col">
        <ul class="list-group mb-3">
        <li class="list-group-item d-flex justify-content-between lh-condensed">
            <div>
            <h6 class="my-0 mb-2">{{$plan->name}}</h6>
                <ul class="list-unstyled">
                    <li class="text-small">
                        <b>{{!isset($plan->max_clients)?'Unlimited':$plan->max_clients}}</b> {{__('client(s)')}}
                    </li>
                    <li class="text-small">
                        <b>{{!isset($plan->max_projects)?'Unlimited':$plan->max_projects}}</b> {{__('project(s)')}}
                    </li>
                    <li class="text-small">
                        <b>{{!isset($plan->max_users)?'Unlimited':($plan->max_users==0?'No':$plan->max_users)}}</b> {{__('collaborator(s)')}}
                    </li>
                </ul>
            </div>
            <span class="text-muted">{{\Auth::user()->priceFormat($plan->price)}}</span>
        </li>
        {{-- <li class="list-group-item d-flex justify-content-between bg-light">
            <div class="text-success">
            <h6 class="my-0">Promo code</h6>
            <small>EXAMPLECODE</small>
            </div>
            <span class="text-success">-$5</span>
        </li> --}}
        <li class="list-group-item d-flex justify-content-between">
            <span>{{__('Total')}}</span>
            <strong>{{\Auth::user()->priceFormat($plan->price)}}</strong>
        </li>
        </ul>

        <div class="input-group">
            <input type="text" class="form-control" placeholder={{__("Promo code")}}>
            <div class="input-group-append">
            <button type="submit" class="btn btn-secondary disabled">{{__('Redeem')}}</button>
            </div>
        </div>
    </div>
</div>
<hr>
<h6 class="mb-0">{{__('Payment method')}}</h6>
<div id="dropin-container" class="mt-0 mb-3"></div>
    <button id="submit-button" class="btn btn-primary float-right">{{__('Pay')}}</button>
</div>
</div>

@endsection

@section('footer')
@endsection

@section('form-end')
@endsection

