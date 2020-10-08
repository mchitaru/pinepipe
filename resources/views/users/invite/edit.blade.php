@extends('layouts.auth')

@php
use Illuminate\Support\Facades\URL;    
@endphp

@section('content')

<div class="container pt-4">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-7">
        <div class="text-center">
            <h1 class="h2">{{ __('Create an account') }}</h1>
            <p class="lead">{{ __('Please fill in the required information below, to finish setting up your account.') }}</p>

            {{Form::open(array('url' => URL::signedRoute('users.invite.update', [$user->id]), 'method'=>'put'))}}
            <div class="form-group">
                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Name')))}}
            </div>
            <div class="form-group">
                {{Form::password('password',array('class'=>'form-control','placeholder'=>__('Password')))}}
            </div>
            <div class="form-group">
                {{Form::password('password_confirmation',array('class'=>'form-control','placeholder'=>__('Confirm Password')))}}
            </div>
            @include('partials.errors')
            <div class="form-group mb-0 text-center">
                {{Form::submit(__('Sign Up'),array('class'=>'btn btn-primary btn-block','id'=>'saveBtn'))}}
            </div>
            {{Form::close()}}
            <small>{{ __('Already Have Account?') }} <a href="{{ route('login') }}">{{ __('Log In') }}</a>
            </small>
        </div>
        </div>
    </div>
</div>

@endsection
