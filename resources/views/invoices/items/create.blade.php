@extends('layouts.modal')

@php
    $isTimesheet = (empty(session('type')) || session('type') == 'timesheet');
    $isTask = (session('type') == 'task');
    $isExpense = (session('type') == 'expense');
    $isOther = (session('type') == 'other');
@endphp

@section('form-start')
    {{ Form::model($invoice, array('route' => array('invoices.items.store', $invoice->id), 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Add Invoiced Item')}}
@endsection

@section('content')
<div class="tab-content">
    <h6>{{__('Project Name')}}</h6>
    <div class="form-group row align-items-center">
        <input type="text" class="form-control col" value="{{$invoice->project->name}}" readonly>
    </div>
    <hr>
    <h6>{{__('What is Invoiced?')}}</h6>

    <div class="accordion" id="productAccordion">
        <div class="card mb-0">
          <div class="card-header p-1" id="headingOne">
            <h5 class="mb-0">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="type" id="timesheet" value="timesheet" data-toggle="collapse" data-target="#collapseOne" aria-expanded="{{$isTimesheet?'true':'false'}}" aria-controls="collapseOne" {{$isTimesheet?'checked':''}}>
                    {{ Form::label('timesheet', __('Timesheet'), array('class'=>'custom-control-label')) }}
                </div>
            </h5>
          </div>

          <div id="collapseOne" class="collapse {{$isTimesheet?'show':''}}" aria-labelledby="headingOne" data-parent="#productAccordion">
            <div class="card-body">
                <div class="form-group row">
                    {{ Form::label('timesheet_id', __('Timesheet'), array('class'=>'col-3')) }}
                    {!! Form::select('timesheet_id', $timesheets, null, array('class' => 'form-control col', 'placeholder'=>__('Select Timesheet...'), 'style'=>'width: 310.5px',
                                        'data-refresh'=>route('invoices.items.refresh', $invoice->id))) !!}
                </div>
            </div>
          </div>
        </div>
        <div class="card mb-0">
          <div class="card-header p-1" id="headingTwo">
            <h5 class="mb-0">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="type" id="task" value="task" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="{{$isTask?'true':'false'}}" aria-controls="collapseTwo" {{$isTask?'checked':''}}>
                    {{ Form::label('task', __('Task'), array('class'=>'custom-control-label')) }}
                </div>
            </h5>
          </div>
          <div id="collapseTwo" class="collapse {{$isTask?'show':''}}" aria-labelledby="headingTwo" data-parent="#productAccordion">
            <div class="card-body">
                <div class="form-group row">
                    {{ Form::label('task_id', __('Task'), array('class'=>'col-3')) }}
                    {!! Form::select('task_id', $tasks, null, array('class' => 'form-control col', 'placeholder'=>__('Select Task...'), 'style'=>'width: 310.5px',
                                        'data-refresh'=>route('invoices.items.refresh', $invoice->id))) !!}
                </div>
            </div>
          </div>
        </div>
        <div class="card mb-0">
            <div class="card-header p-1" id="headingThree">
              <h5 class="mb-0">
                  <div class="custom-control custom-radio">
                      <input type="radio" class="custom-control-input" name="type" id="expense" value="expense" data-toggle="collapse" data-target="#collapseThree" aria-expanded="{{$isExpense?'true':'false'}}" aria-controls="collapseThree" {{$isExpense?'checked':''}}>
                      {{ Form::label('expense', __('Expense'), array('class'=>'custom-control-label')) }}
                  </div>
              </h5>
            </div>
            <div id="collapseThree" class="collapse {{$isExpense?'show':''}}" aria-labelledby="headingThree" data-parent="#productAccordion">
              <div class="card-body">
                  <div class="form-group row">
                      {{ Form::label('expense_id', __('Expense'), array('class'=>'col-3')) }}
                      {!! Form::select('expense_id', $expenses, null, array('class' => 'form-control col', 'placeholder'=>__('Select Expense...'), 'style'=>'width: 310.5px',
                                          'data-refresh'=>route('invoices.items.refresh', $invoice->id))) !!}
                  </div>
              </div>
            </div>
          </div>
          <div class="card mb-0">
          <div class="card-header p-1" id="headingFour">
            <h5 class="mb-0">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" name="type" id="other" value="other" data-toggle="collapse" data-toggle="collapse" data-target="#collapseFour" aria-expanded="{{$isOther?'true':'false'}}" aria-controls="collapseFour" {{$isOther?'checked':''}}>
                    {{ Form::label('other', __('Other'), array('class'=>'custom-control-label',
                                        'data-refresh'=>route('invoices.items.refresh', $invoice->id))) }}
                </div>
            </h5>
          </div>
          <div id="collapseFour" class="collapse {{$isOther?'show':''}}" aria-labelledby="headingFour" data-parent="#productAccordion">
          </div>
        </div>
    </div>
    <hr>
    <div class="form-group row">
        {{ Form::label('text', __('Description'), array('class'=>'col-3')) }}
        {{ Form::textarea('text', null, array('class' => 'form-control col', 'rows' => 3,'placeholder'=>__('Website Redesign'))) }}
    </div>
    <hr>
    <div class="form-group row">
        {{ Form::label('quantity', __('Quantity'), array('class'=>'col-3')) }}
        {{ Form::number('quantity', number_format(1.00, 2), array('class' => 'form-control col','placeholder'=>\Auth::user()->priceFormat(500), 'min'=>'0', 'step'=>'0.1')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('price', __('Price'), array('class'=>'col-3')) }}
        {{ Form::number('price', number_format($price, 2), array('class' => 'form-control col','placeholder'=>\Auth::user()->priceFormat(500), 'min'=>'0', 'step'=>'0.01')) }}
    </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Add'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
