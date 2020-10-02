@extends('layouts.modal')

@php
    $isTimesheet = (empty(session('type')) || session('type') == 'timesheet');
    $isTask = (session('type') == 'task');
    $isExpense = (session('type') == 'expense');
    $isOther = (session('type') == 'other');
@endphp

@section('form-start')
    {{ Form::model($item, array('route' => array('invoices.items.update', $invoice->id, $item->id), 'method' => 'PUT', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Edit Invoiced Item')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row">
        {{ Form::label('text', __('Description'), array('class'=>'col-3')) }}
        {{ Form::textarea('text', null, array('class' => 'form-control col', 'rows' => 3,'placeholder'=>__('Website Redesign'))) }}
    </div>
    <hr>
    <div class="form-group row align-items-center">
        {{ Form::label('quantity', __('Quantity'), array('class'=>'col-3')) }}
        {{ Form::number('quantity', $item->quantity, array('class' => 'form-control col', 'min'=>'0.001', 'step'=>'0.001')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('price', __('Price'), array('class'=>'col-3')) }}
        <div class="input-group col p-0">
            <div class="input-group-prepend">
                <span class="input-group-text">{{Helpers::getCurrencySymbol($invoice->getCurrency())}}</span>
            </div>
            {{ Form::number('price', number_format($item->price, 2, '.', ''), array('class' => 'form-control col','placeholder'=>500, 'min'=>'0', 'step'=>'0.01')) }}
        </div>
    </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Update'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
