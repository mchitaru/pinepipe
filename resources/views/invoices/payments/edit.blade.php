@extends('layouts.modal')

@section('form-start')
    {{ Form::model($payment, array('route' => array('invoices.payments.update', $invoice->id, $payment->id), 'method' => 'PUT', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Update Payment')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row align-items-center required">
        {{ Form::label('amount', __('Amount'), array('class'=>'col-3')) }}
        {{ Form::number('amount', null, array('class' => 'form-control col','required'=>'required',"step"=>"0.01")) }}
    </div>
    <div class="form-group row required">
        {{ Form::label('date', __('Payment Date'), array('class'=>'col-3')) }}
        {{ Form::text('date', null, array('class' => 'form-control col','required'=>'required', 'placeholder'=>'...',
                                        'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-default-date'=> date('Y-m-d'), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('category_id', __('Payment Method'), array('class'=>'col-3')) }}
        {{ Form::select('category_id', $categories, null, array('class' => 'tags form-control col', 'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) }}
    </div>
    <div class="form-group row">
        {{ Form::label('notes', __('Notes'), array('class'=>'col-3')) }}
        {{ Form::textarea('notes', null, array('class' => 'form-control col','rows'=>'3')) }}
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
