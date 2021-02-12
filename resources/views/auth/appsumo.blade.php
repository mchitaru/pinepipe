@extends('layouts.auth')

@section('content')

<div class="container pt-4">
    <div class="row justify-content-center">
        <div class="text-center mb-5">            
            <img class="p-1" width=250 alt="Pinepipe" src="{{ asset('assets/img/logo-dark-full.png') }}" />                
            <img class="p-1" alt="love" src="{{ asset('assets/img/love.png') }}" />                
            <img class="p-1" width=250 alt="AppSumo" src="{{ asset('assets/img/logo-appsumo.png') }}" />                
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-7">
        <div class="text-center">
            <h1 class="h2">{{ __('Create an account') }}</h1>
            <p class="lead">{{ __('Start doing things for free, in an instant') }}</p>
            {{Form::open(array('route'=>'users.appsumo.store', 'method'=>'post'))}}
            <div class="form-group">
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Name'), 'title'=>__('Name')))}}
            </div>
            <div class="form-group">
                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Email Address'),'title'=>__('Email Address')))}}
            </div>
            <div class="form-group">
                {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Password'),'title'=>__('Password')))}}
            </div>
            <div class="form-group">
                {{Form::password('password_confirmation',array('class'=>'form-control','placeholder'=>__('Confirm Password'),'title'=>__('Confirm Password')))}}
            </div>
            <div class="form-group">
                {{Form::text('code', request()->code, array('class'=>'form-control', 'placeholder'=>__('AppSumo Code'), 'title'=>__('AppSumo Code')))}}
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
