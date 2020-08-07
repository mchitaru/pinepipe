@extends('layouts.modal')

@section('form-start')
    {{ Form::model($invoice, array('route' => array('invoices.update', $invoice->id), 'method' => 'PUT', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Edit Invoice')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row align-items-center required">
        {{ Form::label('project', __('Project'), array('class'=>'col-3')) }}
        {{ Form::text('project', $invoice->project->name, array('class' => 'form-control col', 'required'=>'required', 'disabled')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('issue_date', __('Issue Date'), array('class'=>'col-3')) }}
        {{ Form::date('issue_date', null, array('class' => 'start form-control col','required'=>'required', 'placeholder'=>'...',
                                            'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-default-date'=> $issue_date, 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('due_date', __('Due Date'), array('class'=>'col-3')) }}
        {{ Form::date('due_date', null, array('class' => 'end form-control col','required'=>'required', 'placeholder'=>'...',
                                            'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-default-date'=> $due_date, 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('discount', __('Discount(%)'), array('class'=>'col-3')) }}
        {{ Form::number('discount',null, array('class' => 'form-control col', 'required'=>'required', 'min'=>"0")) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('tax_id', __('Tax'), array('class'=>'col-3')) }}
        {{ Form::select('tax_id', $taxes, null, array('class' => 'form-control col', 'placeholder'=>__('No Tax'), 'lang'=>\Auth::user()->locale)) }}
    </div>
    {{-- <div class="form-group row align-items-center">
        {{Form::label('currency',__('Currency'), array('class'=>'col-3')) }}
        {!! Form::select('currency', $currencies, $currency, array('class' => 'form-control col', 
                            'data-refresh'=>route('invoices.refresh', $invoice), 'lang'=>\Auth::user()->locale)) !!}
        @if($currency != \Auth::user()->getCurrency())
        {{Form::label('rate',__('Exchange Rate'), array('class'=>'col-3')) }}
        {!! Form::text('rate', $rate, array('class' => 'form-control col', 'lang'=>\Auth::user()->locale)) !!}
        @else
        {!! Form::hidden('rate', 1.0) !!}
        @endif
    </div> --}}
    <div class="form-group row align-items-center">
        {{Form::label('locale',__('Language'), array('class'=>'col-3')) }}
        {!! Form::select('locale', $locales, $locale, array('class' => 'form-control col', 'lang'=>\Auth::user()->locale)) !!}
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
