@extends('layouts.modal')

@section('form-start')
    {{ Form::model($invoice, array('route' => array('invoices.payments.store', $invoice->id), 'method' => 'POST')) }}
@endsection

@section('title')
    {{__('Create Payment')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row align-items-center required">
        {{ Form::label('amount', __('Amount'), array('class'=>'col-3')) }}
        <div class="input-group col p-0">
            <div class="input-group-prepend">
                <span class="input-group-text">{{Helpers::getCurrencySymbol($invoice->getCurrency())}}</span>
            </div>
            {{ Form::number('amount', $invoice->getDue(), array('class' => 'form-control col','required'=>'required',"step"=>"0.01")) }}
        </div>
    </div>
    <div class="form-group row align-items-center required">
        {{ Form::label('date', __('Payment Date'), array('class'=>'col-3')) }}
        {{ Form::text('date', null, array('class' => 'form-control col','required'=>'required', 'placeholder'=>'...',
                                        'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-default-date'=> date('Y-m-d'), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('category_id', __('Payment Method'), array('class'=>'col-3')) }}
        {{ Form::select('category_id', $categories, null, array('class' => 'tags form-control col', 'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) }}
    </div>
    <div class="row p-1 align-items-center">
        <div class="form-group col custom-control custom-checkbox custom-checkbox-switch d-flex justify-content-end">
            <input type="hidden" name="receipt" value="0">
            {{Form::checkbox('receipt', 1, 0, ['class'=>'custom-control-input', 'id' =>'receipt'])}}
            {{Form::label('receipt', __('Issue Receipt'), ['class'=>'custom-control-label'])}}
        </div>
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
