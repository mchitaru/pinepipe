@extends('layouts.modal')

@section('form-start')
@endsection

@section('title')
    {{__('Secure Card Payment')}}
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

            subscription = '{{$plan_id}}';

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

<div id="dropin-container"></div>
    <button id="submit-button" class="btn btn-primary float-right">Pay</button>
</div>

@endsection

@section('footer')
@endsection

@section('form-end')
@endsection

