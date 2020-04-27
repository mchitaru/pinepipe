@extends('layouts.app')

@php
use App\PaymentPlan;
@endphp

@push('stylesheets')
@endpush

@push('scripts')
<!-- Load the required client component. -->
<script src="https://js.braintreegateway.com/web/3.58.0/js/client.min.js"></script>

<!-- Load Hosted Fields component. -->
<script src="https://js.braintreegateway.com/web/3.58.0/js/hosted-fields.min.js"></script>

<script src="https://js.braintreegateway.com/web/dropin/1.8.1/js/dropin.min.js"></script>

<script>

    $(".avatar-input").change(function () {
        PreviewAvatarImage(this, 60, 'rounded');
    });

    $(document).ready(function() {

        $('a[data-toggle="tab"]').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
            var id = $(e.target).attr("href");
            sessionStorage.setItem('profile.tab', id)
        });

        var selectedTab = sessionStorage.getItem('profile.tab');

        if(selectedTab == null) selectedTab = '#personal';

        $('a[data-toggle="tab"][href="' + selectedTab + '"]').tab('show');


        $('select').select2();
    });

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
            <a class="nav-link" id="personal-tab" data-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="true">{{__('Personal Info')}}</a>
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
        @if(Gate::check('manage plan') && \Auth::user()->type!='super admin')
        <li class="nav-item">
            <a class="nav-link" id="subscription-tab" data-toggle="tab" href="#subscription" role="tab" aria-controls="subscription" aria-selected="false">{{__('Subscription')}}</a>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" id="integrations-tab" data-toggle="tab" href="#integrations" role="tab" aria-controls="integrations" aria-selected="false">{{__('Integrations')}}</a>
        </li>
        </ul>
    </div>
    <div class="col-xl-8 col-lg-10">
        <div class="card">
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show" role="tabpanel" id="personal">@include('users.partials.personal')</div>
                <div class="tab-pane fade" role="tabpanel" id="password">@include('users.partials.password')</div>
                @can('manage company settings')
                <div class="tab-pane fade show" role="tabpanel" id="company">@include('users.partials.company')</div>
                @endcan
                <div class="tab-pane fade" role="tabpanel" id="notifications">@include('users.partials.notifications')</div>
                @if(Gate::check('manage plan') && \Auth::user()->type!='super admin')
                <div class="tab-pane fade" role="tabpanel" id="subscription">@include('users.partials.subscription')</div>
                @endif
                <div class="tab-pane fade" role="tabpanel" id="integrations">@include('users.partials.integrations')</div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection
