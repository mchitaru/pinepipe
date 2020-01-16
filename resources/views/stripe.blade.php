@extends('layouts.admin')
@push('script-page')

    <script src="{{ asset('assets/js/jquery.inputmask.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/jquery.input-ip-address-control-1.0.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/form-input-mask.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script type="text/javascript">
        $("#mask_card_number").inputmask("9999-9999-9999-9999", {
            placeholder: "0000-0000-0000-0000",
            clearMaskOnLostFocus: true
        });

        $("#mask_expiry_date").inputmask("m/y", {
            "placeholder": "MM/YYYY"
        });

        $("#mask_card_code").inputmask("999", {
            placeholder: "000",
            clearMaskOnLostFocus: true
        });

        $(function() {
            var $form = $(".require-validation");

            $('form.require-validation').bind('submit', function(e) {
                var $form = $(".require-validation"),
                    valid = true,
                    $errorMessage = $form.find('div.error');
                $errorMessage.hide();

                $('.has-error').removeClass('has-error');
                $form.find('.required').each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.show();
                        valid = false;
                    }
                });
                if(!valid)
                {
                    return false;
                }
                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    $form.find('[type="submit"]').attr('disabled','disabled');
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    var expiry = $("#mask_expiry_date").val().split("/");
                    Stripe.createToken({
                        number: $('#mask_card_number').val(),
                        cvc: $('#mask_card_code').val(),
                        exp_month: expiry[0],
                        exp_year: expiry[1]
                    }, stripeResponseHandler);
                }
            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    toastrs('Error',response.error.message,'error');
                    $form.find('[type="submit"]').removeAttr('disabled');
                } else {
                    // token contains id, last4, and card type
                    var token = response['id'];
                    // insert the token into the form so it gets submitted to the server
                    $form.find('input[type=text]').not('#mask_card_name').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $form.find('[type="submit"]').attr('disabled','disabled');
                    $form.get(0).submit();
                }
            }

        });
    </script>
@endpush
@php
    $dir= asset(Storage::url('plan'));
       $dir_payment= asset(Storage::url('payments'));
@endphp
@section('page-title')
    {{__('Order Summary')}}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{__('Order Summary')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></div>
                <div class="breadcrumb-item active"><a href="{{ route('plans.index') }}">{{__('Plan')}}</a></div>
                <div class="breadcrumb-item">{{__('Order Summary')}}</div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between w-100">
                            <h4>{{__('Manage Plan')}}</h4>
                            @can('create plan')
                                <a href="#" class="btn btn-sm btn-warning" data-url="{{ route('plans.create') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Create New Plan')}}">
                                  <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 49.861 49.861"><path d="M45.963 21.035h-17.14V3.896C28.824 1.745 27.08 0 24.928 0s-3.896 1.744-3.896 3.896v17.14H3.895C1.744 21.035 0 22.78 0 24.93s1.743 3.895 3.895 3.895h17.14v17.14c0 2.15 1.744 3.896 3.896 3.896s3.896-1.744 3.896-3.896v-17.14h17.14c2.152 0 3.896-1.744 3.896-3.895a3.9 3.9 0 0 0-3.898-3.896z" fill="#010002"/></svg>
                                  </span>
                                    {{__('Create')}}
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="staff-wrap">
                            <div class="row">
                                <div class="col-12 col-md-3 col-lg-3">
                                    <div class="pricing">
                                        <div class="pricing-title">
                                            {{$plan->name}}
                                        </div>
                                        <div class="flex mt-3">
                                            @if(!empty($plan->image))
                                                <img class="plan-img" src="{{$dir.'/'.$plan->image}}">
                                            @endif
                                        </div>
                                        <div class="pricing-padding">
                                            <div class="pricing-price">
                                                <div> ${{$plan->price}}</div>
                                                <div>{{$plan->duration}}</div>
                                            </div>
                                            <div class="pricing-details">
                                                <div class="pricing-item">
                                                    <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                                                    <div class="pricing-item-label">{{$plan->max_users}} {{__('Users')}}</div>
                                                </div>
                                                <div class="pricing-item">
                                                    <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                                                    <div class="pricing-item-label">{{$plan->max_clients}} {{__('Clients')}}</div>
                                                </div>
                                                <div class="pricing-item">
                                                    <div class="pricing-item-icon"><i class="fas fa-check"></i></div>
                                                    <div class="pricing-item-label">{{$plan->max_projects}} {{__('Projects')}}</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="col-md-8">
                                                <h5 class="h3 mb-0">{{__('Credit / Debit Card')}}</h5>
                                                <p>{{__('Safe money transfer using your bank account. We support')}}
                                                    {{__('Mastercard, Visa, Discover and American express.')}}</p>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <div class="block block-2">
                                                    <img src="{{$dir_payment.'/master.png'}}" height="24" alt="master-card-img">
                                                    <img src="{{$dir_payment.'/discover.png'}}" height="24" alt="discover-card-img">
                                                    <img src="{{$dir_payment.'/visa.png'}}" height="24" alt="visa-card-img">
                                                    <img src="{{$dir_payment.'/american express.png'}}" height="24" alt="american-express-card-img">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            {{ Form::open(array('route' => array('stripe.post'),'data-cc-on-file'=>'false','data-stripe-publishable-key'=>env("STRIPE_KEY"),'id'=>'payment-form','class'=>'stripe_form require-validation','method'=>'post')) }}
                                            <div class="row">
                                                <input type="hidden" name="plan" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">
                                                <div class="form-group col-md-6">
                                                    {{Form::label('card_number',__('Card Number'))}}
                                                    {{Form::text('card_number',null,array('class'=>'form-control','id'=>'mask_card_number','required'=>'required'))}}
                                                    @error('card_number')
                                                    <span class="invalid-card_number" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('card_name',__('Name on card'))}}
                                                    {{Form::text('card_name',null,array('class'=>'form-control','id'=>'mask_card_name','placeholder'=>'Company','required'=>'required'))}}
                                                    @error('card_name')
                                                    <span class="invalid-card_name" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('expiry_date',__('Expiry date'))}}
                                                    {{Form::text('expiry_date',null,array('class'=>'form-control','id'=>'mask_expiry_date','required'=>'required'))}}
                                                    @error('expiry_date')
                                                    <span class="invalid-expiry_date" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {{Form::label('code',__('CVV code'))}}
                                                    {{Form::text('code',null,array('class'=>'form-control','id'=>'mask_card_code','required'=>'required'))}}
                                                    @error('code')
                                                    <span class="invalid-code" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12 text-right">
                                                    {{Form::submit(__('Pay Now'),array('class'=>'btn btn-primary'))}}
                                                </div>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
