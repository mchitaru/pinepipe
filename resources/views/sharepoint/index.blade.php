@extends('layouts.app')

@push('stylesheets')
<link href='assets/module/fullcalendar/css/all.min.css' rel='stylesheet' />
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endpush

@push('scripts')
<script src="{{asset('assets/module/fullcalendar/js/all.min.js')}}"></script>
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
@endpush

@section('page-title')
    {{__('Sharepoint')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Sharepoint')}}</li>
        </ol>
    </nav>

</div>
@endsection

@section('content')
<div class="container">
    <div class="pt-3" style="height: 800px;">
        <div id="fm"></div>
    </div>
</div>
@endsection
