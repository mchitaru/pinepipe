@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('url' => 'invoices', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Create Invoice')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row align-items-center">
        {{ Form::label('increment', __('Number'), array('class'=>'col-3')) }}
        <div class="input-group col-4 p-0">
            <div class="input-group-prepend">
                <span class="input-group-text light" id="basic-addon3">{{\Auth::user()->invoicePrefix()}}</span>
            </div>
            {{ Form::number('increment', $increment, array('class' => 'form-control col text-right', 'required'=>'required', 'min'=>1)) }}
        </div>
    </div>
    <hr>
    <div class="form-group row align-items-center required">
        {{ Form::label('client_id', __('Client'), array('class'=>'col-3')) }}
        {!! Form::select('client_id', $clients, $client_id, array('class' => 'form-control col', 'required'=>'required', 'placeholder'=>'...',
                            'data-refresh'=>route('invoices.refresh','0'), 'lang'=>\Auth::user()->locale)) !!}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('project_id', __('Project'), array('class'=>'col-3')) }}
        {{ Form::select('project_id', $projects, $project_id, array('class' => 'form-control col',
                        'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('issue_date', __('Issue Date'), array('class'=>'col-3')) }}
        {{ Form::date('issue_date', null, array('class' => 'start form-control col bg-white','required'=>'required', 'placeholder'=>'...',
                                            'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-default-date'=> $issue_date, 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('due_date', __('Due Date (inv)'), array('class'=>'col-3')) }}
        {{ Form::date('due_date', null, array('class' => 'end form-control col bg-white','required'=>'required', 'placeholder'=>'...',
                                            'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-default-date'=> $due_date, 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    @if($_user->isTaxPayer())
    <div class="form-group row align-items-center">
        {{ Form::label('tax_id', __('VAT'), array('class'=>'col-3')) }}
        {{ Form::select('tax_id', $taxes, null, array('class' => 'form-control col', 'lang'=>\Auth::user()->locale)) }}
    </div>
    @endif
    <div class="form-group row align-items-center">
        {{Form::label('currency',__('Currency'), array('class'=>'col-3')) }}
        {!! Form::select('currency', $currencies, $currency, array('class' => 'form-control col',
                            'data-refresh'=>route('invoices.refresh', '0'), 'lang'=>\Auth::user()->locale)) !!}
        @if($currency != \Auth::user()->getCurrency())
        {{Form::label('rate',__('Exchange Rate'), array('class'=>'col-2')) }}
        <div class="input-group col p-0">
            <div class="input-group-prepend">
                <span class="input-group-text">{{Helpers::getCurrencySymbol(\Auth::user()->getCurrency())}}</span>
            </div>
            {!! Form::text('rate', $rate, array('class' => 'form-control col', 'lang'=>\Auth::user()->locale)) !!}
        </div>
        @else
        {!! Form::hidden('rate', 1.0) !!}
        @endif
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('locale',__('Language'), array('class'=>'col-3')) }}
        {!! Form::select('locale', $locales, $locale, array('class' => 'form-control col', 'lang'=>\Auth::user()->locale)) !!}
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
