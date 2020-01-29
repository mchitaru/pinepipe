@extends('layouts.modal')

@section('form-start')
    {{ Form::model($invoice, array('route' => array('invoices.payments.store', $invoice->id), 'method' => 'POST')) }}
@endsection

@section('title')
    {{__('Create Invoice')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row align-items-center required">
        {{ Form::label('amount', __('Amount'), array('class'=>'col-3')) }}
        {{ Form::number('amount', $invoice->getDue(), array('class' => 'form-control col','required'=>'required','min'=>'0',"step"=>"0.01")) }}
    </div>
    <div class="form-group row">
        {{ Form::label('date', __('Payment Date'), array('class'=>'col-3')) }}
        {{ Form::text('date', null, array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Select Date',
                                        'data-flatpickr', 'data-default-date'=> date('Y-m-d'), 'data-alt-input')) }}
    </div>
    <div class="form-group row required">
        {{ Form::label('payment_id', __('Payment Method'), array('class'=>'col-3')) }}
        {{ Form::select('payment_id', $payment_methods,null, array('class' => 'form-control col','required'=>'required')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('notes', __('Notes'), array('class'=>'col-3')) }}
        {{ Form::textarea('notes', null, array('class' => 'form-control col','rows'=>'3')) }}
    </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Create'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection