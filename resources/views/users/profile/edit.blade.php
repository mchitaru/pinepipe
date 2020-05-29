@extends('layouts.app')

@php
use App\SubscriptionPlan;
@endphp

@push('stylesheets')
@endpush

@push('scripts')
<script>

    $(".avatar-input").change(function () {
        PreviewAvatarImage(this, 60, 'rounded');
    });

    $(document).ready(function() {

        $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
            window.history.replaceState(null, null, $(e.target).attr('href'));
            window.location.hash = $(e.target).attr('href');

            var id = $(e.target).attr("href");
            sessionStorage.setItem('profile.tab', id);
        });

        var hash = window.location.hash ? window.location.hash : sessionStorage.getItem('profile.tab');

        if(hash == null) hash = '#profile';

        $('a[data-toggle="tab"][href="' + hash + '"]').tab('show');

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

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
    <div class="col-lg-3 mb-3">
        <ul class="nav nav-tabs flex-lg-column" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">{{__('Profile')}}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">{{__('Password')}}</a>
        </li>
        @if(\Auth::user()->type=='company')
        <li class="nav-item">
            <a class="nav-link" id="company-tab" data-toggle="tab" href="#company" role="tab" aria-controls="company" aria-selected="true">{{__('Company')}}</a>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" id="notifications-tab" data-toggle="tab" href="#notifications" role="tab" aria-controls="notifications" aria-selected="false">{{__('Email Notifications')}}</a>
        </li>
        @if(\Auth::user()->type=='company')
        <li class="nav-item">
            <a class="nav-link" id="subscription-tab" data-toggle="tab" href="#subscription" role="tab" aria-controls="subscription" aria-selected="false">{{__('Subscription')}}</a>
        </li>
        @endif
        {{-- <li class="nav-item">
            <a class="nav-link" id="integrations-tab" data-toggle="tab" href="#integrations" role="tab" aria-controls="integrations" aria-selected="false">{{__('Integrations')}}</a>
        </li> --}}
        </ul>
    </div>
    <div class="col-xl-8 col-lg-10">
        <div class="card">
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show" role="tabpanel" id="profile">@include('users.profile.partials.profile')</div>
                <div class="tab-pane fade" role="tabpanel" id="password">@include('users.profile.partials.password')</div>
                @if(\Auth::user()->type=='company')
                <div class="tab-pane fade show" role="tabpanel" id="company">@include('users.profile.partials.company')</div>
                @endif
                <div class="tab-pane fade" role="tabpanel" id="notifications">@include('users.profile.partials.notifications')</div>
                @if(\Auth::user()->type=='company')
                <div class="tab-pane fade" role="tabpanel" id="subscription">@include('users.profile.partials.subscription')</div>
                @endif
                {{-- <div class="tab-pane fade" role="tabpanel" id="integrations">@include('users.profile.partials.integrations')</div> --}}
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection
