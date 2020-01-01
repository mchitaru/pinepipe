@extends('layouts.app')
@php
    $avatar=asset(Storage::url('avatar/'));
@endphp
@push('stylesheets')
@endpush

@push('scripts')
<script>
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
<div class="row justify-content-center mt-5">
<div class="col-lg-3 mb-3">
    <ul class="nav nav-tabs flex-lg-column" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">{{__('Personal Info')}}</a>
    </li>
    @can('change password account')
    <li class="nav-item">
        <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">{{__('Password')}}</a>
    </li>
    @endcan
    <li class="nav-item">
        <a class="nav-link" id="notifications-tab" data-toggle="tab" href="#notifications" role="tab" aria-controls="notifications" aria-selected="false">{{__('Email Notifications')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="billing-tab" data-toggle="tab" href="#billing" role="tab" aria-controls="billing" aria-selected="false">{{__('Billing Details')}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="integrations-tab" data-toggle="tab" href="#integrations" role="tab" aria-controls="integrations" aria-selected="false">{{__('Integrations')}}</a>
    </li>
    </ul>
</div>
<div class="col-xl-8 col-lg-9">
    <div class="card">
    <div class="card-body">
        <div class="tab-content">
        <div class="tab-pane fade show active" role="tabpanel" id="profile">
            {{Form::model($userDetail,array('route' => array('update.account'), 'method' => 'put', 'enctype' => "multipart/form-data"))}}
            <div class="media mb-4">
                <div class="d-flex flex-column">
                    <img alt="image" src="{{(!empty($userDetail->avatar))? $avatar.'/'.$userDetail->avatar : $avatar.'/avatar.png'}}" class="avatar avatar-lg">
                    <span class="badge badge-secondary">{{$userDetail->type}}</span>
                </div>
            <div class="media-body ml-3">
                <div class="custom-file custom-file-naked d-block mb-1">
                    <input type="file" class="custom-file-input d-none" name="avatar" id="avatar">
                    <label class="custom-file-label position-relative" for="avatar">
                      <span class="btn btn-primary">
                        {{__('Upload avatar')}}
                      </span>
                    </label>
                    <label class="file-label position-relative"></label>
                </div>
                <small>{{__('For best results, use an image at least 256px by 256px in either .jpg or .png format')}}</small>
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
                <small>This will be displayed on your public profile</small>
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
            <form>
            <div class="form-group row align-items-center">
                <label class="col-3">Current Password</label>
                <div class="col">
                <input type="password" placeholder="Enter your current password" name="password-current" class="form-control" />
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label class="col-3">New Password</label>
                <div class="col">
                <input type="password" placeholder="Enter a new password" name="password-new" class="form-control" />
                <small>Password must be at least 8 characters long</small>
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label class="col-3">Confirm Password</label>
                <div class="col">
                <input type="password" placeholder="Confirm your new password" name="password-new-confirm" class="form-control" />
                </div>
            </div>
            <div class="row justify-content-end">
                <button type="submit" class="btn btn-primary">Change Password</button>
            </div>
            </form>
        </div>
        <div class="tab-pane fade" role="tabpanel" id="notifications">
            <form>
            <h6>Activity Notifications</h6>
            <div class="form-group">
                <div class="custom-control custom-checkbox custom-checkbox-switch">
                <input type="checkbox" class="custom-control-input" id="notify-1" checked>
                <label class="custom-control-label" for="notify-1">Someone assigns me to a task</label>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox custom-checkbox-switch">
                <input type="checkbox" class="custom-control-input" id="notify-2" checked>
                <label class="custom-control-label" for="notify-2">Someone mentions me in a conversation</label>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox custom-checkbox-switch">
                <input type="checkbox" class="custom-control-input" id="notify-3" checked>
                <label class="custom-control-label" for="notify-3">Someone adds me to a project</label>
                </div>
            </div>
            <div class="form-group mb-md-4">
                <div class="custom-control custom-checkbox custom-checkbox-switch">
                <input type="checkbox" class="custom-control-input" id="notify-4">
                <label class="custom-control-label" for="notify-4">Activity on a project I am a member of</label>
                </div>
            </div>
            <h6>Service Notifications</h6>
            <div class="form-group">
                <div class="custom-control custom-checkbox custom-checkbox-switch">
                <input type="checkbox" class="custom-control-input" id="notify-5">
                <label class="custom-control-label" for="notify-5">Monthly newsletter</label>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox custom-checkbox-switch">
                <input type="checkbox" class="custom-control-input" id="notify-6" checked>
                <label class="custom-control-label" for="notify-6">Major feature enhancements</label>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox custom-checkbox-switch">
                <input type="checkbox" class="custom-control-input" id="notify-7">
                <label class="custom-control-label" for="notify-7">Minor updates and bug fixes</label>
                </div>
            </div>
            <div class="row justify-content-end">
                <button type="submit" class="btn btn-primary">Save preferences</button>
            </div>
            </form>
        </div>
        <div class="tab-pane fade" role="tabpanel" id="billing">
            <form>
            <h6>Plan Details</h6>
            <div class="card text-center">
                <div class="card-body">
                <div class="row">
                    <div class="col">
                    <div class="mb-4">
                        <h6>Free</h6>
                        <h5 class="display-4 d-block mb-2 font-weight-normal">$0</h5>
                        <span class="text-muted text-small">Per User / Per Month</span>
                    </div>
                    <ul class="list-unstyled">
                        <li>
                        Unlimited projects
                        </li>
                        <li>
                        1 team
                        </li>
                        <li>
                        4 team members
                        </li>
                    </ul>
                    <div class="custom-control custom-radio d-inline-block">
                        <input type="radio" id="plan-radio-1" name="customRadio" class="custom-control-input">
                        <label class="custom-control-label" for="plan-radio-1"></label>
                    </div>
                    </div>
                    <div class="col">
                    <div class="mb-4">
                        <h6>Pro</h6>
                        <h5 class="display-4 d-block mb-2 font-weight-normal">$10</h5>
                        <span class="text-muted text-small">Per User / Per Month</span>
                    </div>
                    <ul class="list-unstyled">
                        <li>
                        Unlimited projects
                        </li>
                        <li>
                        Unlmited teams
                        </li>
                        <li>
                        Unlimited team members
                        </li>
                    </ul>
                    <div class="custom-control custom-radio d-inline-block">
                        <input type="radio" id="plan-radio-2" name="customRadio" class="custom-control-input" checked>
                        <label class="custom-control-label" for="plan-radio-2"></label>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </form>
            <form class="mt-4">
            <h6>Payment Method</h6>

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
                    <img alt="Image" src="assets/img/logo-payment-visa.svg" class="avatar rounded-0" />
                    </div>
                    <div class="col d-flex align-items-center">
                    <span>•••• •••• •••• 8372</span>
                    <small class="ml-2">Exp: 06/21</small>
                    </div>
                    <div class="col-auto">
                    <button class="btn btn-sm btn-danger">
                        Remove Card
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
                        <input type="radio" id="method-radio-2" name="payment-method" class="custom-control-input">
                        <label class="custom-control-label" for="method-radio-2"></label>
                    </div>
                    </div>
                    <div class="col-auto">
                    <img alt="Image" src="assets/img/logo-payment-amex.svg" class="avatar rounded-0" />
                    </div>
                    <div class="col d-flex align-items-center">
                    <span>•••• •••• •••• 9918</span>
                    <small class="ml-2">Exp: 02/20</small>
                    </div>
                    <div class="col-auto">
                    <button class="btn btn-sm btn-danger">
                        Remove Card
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
                    <img alt="Image" src="assets/img/logo-payment-paypal.svg" class="avatar rounded-0" />
                    </div>
                    <div class="col d-flex align-items-center">
                    <span>david.whittaker@pipeline.io</span>

                    </div>
                    <div class="col-auto">
                    <button class="btn btn-sm btn-primary">
                        Manage account
                    </button>
                    </div>
                </div>
                </div>
            </div>

            </form>
        </div>
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
                    <button class="btn btn-sm btn-danger">
                    Revoke
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
                    <button class="btn btn-sm btn-danger">
                    Revoke
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
                    <button class="btn btn-sm btn-danger">
                    Revoke
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
                    <button class="btn btn-sm btn-danger">
                    Revoke
                    </button>
                </div>
                </div>
            </div>
            </div>

        </div>
        </div>
    </div>
    </div>
</div>
</div>
@endsection
