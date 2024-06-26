@extends('layouts.auth')

@section('content')

<div class="container pt-4">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-7">
        <div class="text-center">
            <h1 class="h2">{{ __('Create an account') }}</h1>
            <p class="lead">{{ __('Start doing things for free, in an instant') }}</p>
            {{-- <button class="btn btn-lg btn-block btn-primary" disabled>
            <img alt="Google" src="{{asset('assets/img/logo-google.svg')}}" class="rounded align-top mr-2" />{{ __('Continue with Google') }}
            </button>
            <hr> --}}
            {{Form::open(array('route'=>'register','method'=>'post','id'=>'loginForm'))}}
            <div class="form-group">
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Name')))}}
            </div>
            <div class="form-group">
                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Email Address')))}}
            </div>
            <div class="form-group">
                {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Password')))}}
            </div>
            <div class="form-group">
                {{Form::password('password_confirmation',array('class'=>'form-control','placeholder'=>__('Confirm Password')))}}
            </div>
            <div class="form-group row align-items-center justify-content-center">    
                {!!captcha_img()!!}
                {{Form::text('captcha',null,array('class'=>'form-control ml-1 col-2'))}}
            </div>
            @include('partials.errors')
            <div class="form-group mb-0 text-center">
                {{Form::submit(__('Sign Up'),array('class'=>'btn btn-primary btn-block','id'=>'saveBtn'))}}
            </div>
            <small>
                {{__('By clicking \'Sign Up\' you agree to our ')}} <a href={{__("https://pinepipe.com/privacy-policy")}}>{{__('Privacy Policy')}}</a>
            </small>
            <small>
                {{__(' and ')}} <a href={{__("https://pinepipe.com/terms-and-conditions")}}>{{__('Terms and Conditions')}}</a>
            </small>
            {{Form::close()}}
            <small>{{ __('Already Have Account?') }} <a href="{{ route('login') }}">{{ __('Log In') }}</a>
            </small>
        </div>
        </div>
    </div>
</div>


@endsection
