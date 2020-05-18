@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('page-title')
    {{__('Invoice Detail')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top d-print-none">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="{{ route('invoices.index') }}">{{__('Invoices')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ Auth::user()->invoiceNumberFormat($invoice->id) }}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row pt-5 justify-content-center">
        <div class="col-12 col-lg-9">
            <div class="card">
                @include('invoices.invoice')
            </div>
        </div>
    </div>
</div>
@endsection
