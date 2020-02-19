@extends('layouts.app')

@php
use App\Http\Helpers;
@endphp

@push('stylesheets')
@endpush

@push('scripts')
<script>

    $(".avatar-input").change(function () {
        PreviewAvatarImage(this, 60, 'rounded');
    });

    // keep active tab
    $(document).ready(function() {

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) 
        {
            window.history.replaceState(null, null, $(e.target).attr('href'));
            window.location.hash = $(e.target).attr('href');
            $(window).scrollTop(0);
        });
    
        var hash = window.location.hash ? window.location.hash : '#profile';
    
        $('.nav-tabs a[href="' + hash + '"]').tab('show');

    });

    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".file-label").addClass("selected").html(fileName);
    });
    
</script>
@endpush

@section('page-title')
    {{__('User')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Profile')}}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
    <div class="col-lg-3 mb-3">
        <ul class="nav nav-tabs flex-lg-column" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">{{__('Personal Info')}}</a>
        </li>
        @can('change password account')
        <li class="nav-item">
            <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">{{__('Password')}}</a>
        </li>
        @endcan
        @can('manage company settings')
        <li class="nav-item">
            <a class="nav-link" id="company-tab" data-toggle="tab" href="#company" role="tab" aria-controls="profile" aria-selected="true">{{__('Company Info')}}</a>
        </li>
        @endcan
        <li class="nav-item">
            <a class="nav-link" id="notifications-tab" data-toggle="tab" href="#notifications" role="tab" aria-controls="notifications" aria-selected="false">{{__('Email Notifications')}}</a>
        </li>
        @if(Gate::check('manage plan'))
        <li class="nav-item">
            <a class="nav-link" id="billing-tab" data-toggle="tab" href="#billing" role="tab" aria-controls="billing" aria-selected="false">{{__('Billing Details')}}</a>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" id="integrations-tab" data-toggle="tab" href="#integrations" role="tab" aria-controls="integrations" aria-selected="false">{{__('Integrations')}}</a>
        </li>
        @can('manage company settings')
        <li class="nav-item">
            <a class="nav-link" id="system-tab" data-toggle="tab" href="#system" role="tab" aria-controls="profile" aria-selected="true">{{__('System Settings')}}</a>
        </li>
        @endcan
        </ul>
    </div>
    <div class="col-xl-8 col-lg-10">
        <div class="card">
        <div class="card-body">
            <div class="tab-content">
            <div class="tab-pane fade show" role="tabpanel" id="profile">
                {{Form::model($user,array('route' => array('profile.update'), 'method' => 'put', 'enctype' => "multipart/form-data"))}}
                <div class="media mb-4 avatar-container">
                    <div class="d-flex flex-column avatar-preview">
                        {!!Helpers::buildAvatar($user, 60, 'rounded')!!}
                    </div>
                    <div class="media-body ml-3">
                        <div class="custom-file custom-file-naked d-block mb-1">
                            <input type="file" class="custom-file-input avatar-input d-none" name="avatar" id="avatar">
                            <label class="custom-file-label position-relative" for="avatar">
                            <span class="btn btn-primary">
                                {{__('Upload avatar')}}
                            </span>
                            </label>
                            <label class="file-label position-relative d-none"></label>
                        </div>
                        <div class="alert alert-warning text-small" role="alert">
                            <small>{{__('For best results, use an image at least 256px by 256px in either .jpg or .png format')}}</small>
                        </div>            
                    </div>
                </div>
                <!--end of avatar-->
                <div class="form-group row align-items-center">
                    {{Form::label('name',__('Name'), array('class'=>'col-3'))}}
                    <div class="col">
                        {{Form::text('name',null,array('class'=>'form-control','placeholder'=>_('Enter User Name')))}}
                        @error('name')
                        <span class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>                    
                </div>
                <div class="form-group row align-items-center">
                    {{Form::label('email',__('Email'), array('class'=>'col-3'))}}
                    <div class="col">
                        {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))}}
                        @error('email')
                        <span class="invalid-email" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>                    
                </div>
                <div class="form-group row">
                    <label class="col-3">Bio</label>
                    <div class="col">
                    <textarea placeholder="Tell us a little about yourself" name="profile-bio" class="form-control" rows="4"></textarea>
                    <small>{{__('This will be displayed on your public profile')}}</small>
                    </div>
                </div>
                <div class="row justify-content-end">
                    @can('edit account')
                        {{Form::submit('Save',array('class'=>'btn btn-primary'))}}
                    @endcan
                </div>
                {{Form::close()}}
            </div>
            <div class="tab-pane fade" role="tabpanel" id="password">
                {{Form::model($user,array('route' => array('profile.password',$user->id), 'method' => 'patch'))}}
                <div class="form-group row align-items-center">
                    {{Form::label('current_password',__('Current Password'), array('class'=>'col-3'))}}
                    <div class="col">
                        {{Form::password('current_password',array('class'=>'form-control','placeholder'=>_('Enter Current Password')))}}
                        @error('current_password')
                        <span class="invalid-current_password" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    {{Form::label('new_password',__('New Password'), array('class'=>'col-3'))}}
                    <div class="col">
                        {{Form::password('new_password',array('class'=>'form-control','placeholder'=>_('Enter New Password')))}}
                        @error('new_password')
                        <span class="invalid-new_password" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    {{Form::label('new_password_confirmation',__('Confirm Password'), array('class'=>'col-3'))}}
                    <div class="col">
                        {{Form::password('new_password_confirmation',array('class'=>'form-control','placeholder'=>_('Confirm Password')))}}
                        @error('new_password_confirmation')
                        <span class="invalid-confirm_password" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row justify-content-end">
                    {{Form::submit('Change Password',array('class'=>'btn btn-primary'))}}
                </div>
                {{Form::close()}}
            </div>
            @can('manage company settings')
            <div class="tab-pane fade show" role="tabpanel" id="company">
                {{Form::model($user->settings,array('route'=>'settings.company','method'=>'post', 'enctype' => 'multipart/form-data'))}}
                <div class="card-body">
                    <div class="row">
                        <div class="media mb-4 avatar-container">
                            <div class="d-flex flex-column avatar-preview">
                                <img width="60" height="60" alt="{{$user->settings['company_name']}}" {!! empty($user->settings['company_logo']) ? "avatar='".$user->settings['company_name']."'" : "" !!} class="rounded" src="{{!empty($user->settings['company_logo'])?Storage::url($user->settings['company_logo']):""}}" data-filter-by="alt"/>
                            </div>
                            <div class="media-body ml-3">
                                <div class="custom-file custom-file-naked d-block mb-1">
                                    <input type="file" class="custom-file-input avatar-input d-none" name="company_logo" id="company_logo">
                                    <label class="custom-file-label position-relative" for="company_logo">
                                    <span class="btn btn-primary">
                                        {{__('Upload logo')}}
                                    </span>
                                    </label>
                                    <label class="file-label position-relative d-none"></label>
                                </div>
                                <div class="alert alert-warning text-small" role="alert">
                                    <small>{{__('For best results, use an image at least 256px by 256px in either .jpg or .png format')}}</small>
                                </div>            
                            </div>
                        </div>
                        <!--end of logo-->
        
                        <div class="form-group col-md-6">
                            {{Form::label('company_name *',__('Company Name *')) }}
                            {{Form::text('company_name',null,array('class'=>'form-control font-style'))}}
                            @error('company_name')
                            <span class="invalid-company_name" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            {{Form::label('company_address',__('Address')) }}
                            {{Form::text('company_address',null,array('class'=>'form-control font-style'))}}
                            @error('company_address')
                            <span class="invalid-company_address" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            {{Form::label('company_city',__('City')) }}
                            {{Form::text('company_city',null,array('class'=>'form-control font-style'))}}
                            @error('company_city')
                            <span class="invalid-company_city" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            {{Form::label('company_state',__('State')) }}
                            {{Form::text('company_state',null,array('class'=>'form-control font-style'))}}
                            @error('company_state')
                            <span class="invalid-company_state" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            {{Form::label('company_zipcode',__('Zip/Post Code')) }}
                            {{Form::text('company_zipcode',null,array('class'=>'form-control'))}}
                            @error('company_zipcode')
                            <span class="invalid-company_zipcode" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group  col-md-6">
                            {{Form::label('company_country',__('Country')) }}
                            {{Form::text('company_country',null,array('class'=>'form-control font-style'))}}
                            @error('company_country')
                            <span class="invalid-company_country" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            {{Form::label('company_phone',__('Phone')) }}
                            {{Form::text('company_phone',null,array('class'=>'form-control'))}}
                            @error('company_phone')
                            <span class="invalid-company_phone" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            {{Form::label('company_email',__('System Email *')) }}
                            {{Form::text('company_email',null,array('class'=>'form-control'))}}
                            @error('company_email')
                            <span class="invalid-company_email" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            {{Form::label('company_email_from_name',__('Email (From Name) *')) }}
                            {{Form::text('company_email_from_name',null,array('class'=>'form-control font-style'))}}
                            @error('company_email_from_name')
                            <span class="invalid-company_email_from_name" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end">
                    {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                </div>
                {{Form::close()}}
            </div>
            @endcan
            <div class="tab-pane fade" role="tabpanel" id="notifications">
                <form>
                <h6>{{__('Activity Notifications')}}</h6>
                <div class="form-group">
                    <div class="custom-control custom-checkbox custom-checkbox-switch">
                    <input type="checkbox" class="custom-control-input" id="notify-1" checked>
                    <label class="custom-control-label" for="notify-1">{{__('Someone assigns me to a task')}}</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox custom-checkbox-switch">
                    <input type="checkbox" class="custom-control-input" id="notify-3" checked>
                    <label class="custom-control-label" for="notify-3">{{__('Someone adds me to a project')}}</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox custom-checkbox-switch">
                    <input type="checkbox" class="custom-control-input" id="notify-4">
                    <label class="custom-control-label" for="notify-4">{{__('Activity on a project I am a member of')}}</label>
                    </div>
                </div>
                <div class="form-group mb-md-4">
                    <div class="custom-control custom-checkbox custom-checkbox-switch">
                    <input type="checkbox" class="custom-control-input" id="notify-2" checked>
                    <label class="custom-control-label" for="notify-2">{{__('My items (tasks, invoices ...) are overdue')}}</label>
                    </div>
                </div>
                <h6>{{__('Service Notifications')}}</h6>
                <div class="form-group">
                    <div class="custom-control custom-checkbox custom-checkbox-switch">
                    <input type="checkbox" class="custom-control-input" id="notify-5">
                    <label class="custom-control-label" for="notify-5">{{__('Monthly newsletter')}}</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox custom-checkbox-switch">
                    <input type="checkbox" class="custom-control-input" id="notify-6" checked>
                    <label class="custom-control-label" for="notify-6">{{__('Major feature enhancements')}}</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox custom-checkbox-switch">
                    <input type="checkbox" class="custom-control-input" id="notify-7">
                    <label class="custom-control-label" for="notify-7">{{__('Minor updates and bug fixes')}}</label>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <button type="submit" class="btn btn-primary">{{__('Save preferences')}}</button>
                </div>
                </form>
            </div>
            @if(Gate::check('manage plan'))
            <div class="tab-pane fade" role="tabpanel" id="billing">
                {{Form::model($user->settings,array('route'=>'plans.upgrade','method'=>'post'))}}
                <div class="mt-4">
                    <h6>{{__('Subscription')}}</h6>
                    <div class="card text-center">
                        <div class="card-body">
                        <div class="row">
                            @foreach($plans as $key=>$plan)
                            <div class="col-4 mb-4">            
                                <div class="mb-4">
                                    <h6>
                                        {{$plan->name}}
                                        @if(($user->type != 'super admin') && (\Auth::user()->planByUserType()->id == $plan->id))
                                            <span class="badge badge-primary">active</span>
                                        @endif
                                    </h6>
                                    <h3 class="d-block mb-2 font-weight-bold">{{Auth::user()->priceFormat($plan->price)}}</h3>
                                    <span class="text-muted text-small">{{$plan->duration}}</span>
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
                                <div class="custom-control custom-radio d-inline-block">
                                    <input type="radio" id="plan-radio-{{$plan->id}}" name="customRadio" class="custom-control-input" {{(($user->type != 'super admin') && (\Auth::user()->planByUserType()->id == $plan->id))?'checked':''}}>
                                    <label class="custom-control-label" for="plan-radio-{{$plan->id}}"></label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 mb-4">
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


                    <div class="card">
                        <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                            <div class="custom-control custom-radio d-inline-block">
                                <input type="radio" id="method-radio-3" name="payment-method" class="custom-control-input">
                                <label class="custom-control-label" for="method-radio-3"></label>
                            </div>
                            </div>
                            <div class="col-auto">
                            <img alt="Image" src="{{ asset('assets/img/logo-payment-paypal.svg') }}" class="avatar rounded-0" />
                            </div>
                            <div class="col d-flex align-items-center">
                            <span>david.whittaker@pipeline.io</span>

                            </div>
                            <div class="col-auto">
                            <button class="btn btn-sm btn-primary disabled">
                                {{__('Manage account')}}
                            </button>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-end">
                    {{Form::submit('Upgrade',array('class'=>'btn btn-primary disabled'))}}
                </div>
                {{Form::close()}}

            </div>
            @endif
            <div class="tab-pane fade" role="tabpanel" id="integrations">

                <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                    <div class="col">
                        <div class="media align-items-center">
                        <img alt="Image" src="assets/img/logo-integration-slack.svg" />
                        <div class="media-body ml-2">
                            <span class="h6 mb-0 d-block">Slack</span>
                            <span class="text-small text-muted">Permissions: Read, Write, Comment</span>
                        </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-danger disabled">
                        {{__('Revoke')}}
                        </button>
                    </div>
                    </div>
                </div>
                </div>

                <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                    <div class="col">
                        <div class="media align-items-center">
                        <img alt="Image" src="assets/img/logo-integration-dropbox.svg" />
                        <div class="media-body ml-2">
                            <span class="h6 mb-0 d-block">Dropbox</span>
                            <span class="text-small text-muted">Permissions: Read, Write, Upload</span>
                        </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-danger disabled">
                        {{__('Revoke')}}
                        </button>
                    </div>
                    </div>
                </div>
                </div>

                <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                    <div class="col">
                        <div class="media align-items-center">
                        <img alt="Image" src="assets/img/logo-integration-drive.svg" />
                        <div class="media-body ml-2">
                            <span class="h6 mb-0 d-block">Google Drive</span>
                            <span class="text-small text-muted">Permissions: Read, Write</span>
                        </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-danger disabled">
                        {{__('Revoke')}}
                        </button>
                    </div>
                    </div>
                </div>
                </div>

                <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                    <div class="col">
                        <div class="media align-items-center">
                        <img alt="Image" src="assets/img/logo-integration-trello.svg" />
                        <div class="media-body ml-2">
                            <span class="h6 mb-0 d-block">Trello</span>
                            <span class="text-small text-muted">Permissions: Read, Write</span>
                        </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-danger disabled">
                        {{__('Revoke')}}
                        </button>
                    </div>
                    </div>
                </div>
                </div>

            </div>
            @can('manage company settings')
            <div class="tab-pane fade show" role="tabpanel" id="system">
                {{Form::model($settings,array('route'=>'settings.system','method'=>'post'))}}
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            {{Form::label('site_currency',__('Currency')) }}
                            {{Form::text('site_currency',null,array('class'=>'form-control font-style'))}}
                            @error('site_currency')
                            <span class="invalid-site_currency" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="site_date_format" class="form-control-label">{{__('Date Format')}}</label>
                            <select type="text" name="site_date_format" class="form-control selectric" id="site_date_format">
                                <option value="M j, Y" @if(@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>Jan 1,2015</option>
                                <option value="d-m-Y" @if(@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>d-m-y</option>
                                <option value="m-d-Y" @if(@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>m-d-y</option>
                                <option value="Y-m-d" @if(@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>y-m-d</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="site_time_format" class="form-control-label">{{__('Time Format')}}</label>
                            <select type="text" name="site_time_format" class="form-control selectric" id="site_time_format">
                                <option value="g:i A" @if(@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>10:30 PM</option>
                                <option value="g:i a" @if(@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>10:30 pm</option>
                                <option value="H:i" @if(@$settings['site_time_format'] == 'H:i') selected="selected" @endif>22:30</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            {{Form::label('invoice_prefix',__('Invoice Prefix')) }}
                            {{Form::text('invoice_prefix',null,array('class'=>'form-control'))}}
                            @error('invoice_prefix')
                            <span class="invalid-invoice_prefix" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end">
                    {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
                </div>
                {{Form::close()}}
            </div>
            @endcan
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection
