@extends('layouts.modal')

@section('form-start')
    {{ Form::model($expense, array('route' => array('expenses.update', $expense->id), 'method' => 'PUT','enctype' => "multipart/form-data")) }}
@endsection

@section('title')
    {{__('Edit Expense')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row required">
        {{ Form::label('amount', __('Amount'), array('class'=>'col-3')) }}
        {{ Form::number('amount', null, array('class' => 'form-control col','required'=>'required')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('date', __('Date'), array('class'=>'col-3')) }}
        {{ Form::text('date', null, array('class' => 'form-control col','required'=>'required', 'placeholder'=>__('Select Date...'),
                                        'data-flatpickr', 'data-default-date'=> $expense->date, 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('project_id', __('Project'), array('class'=>'col-3')) }}
        {{ Form::select('project_id', $projects, null, array('class' => 'form-control col', 'placeholder'=>__('Select Project...'))) }}
    </div>
    <div class="form-group row">
        {{ Form::label('category_id', __('Category'), array('class'=>'col-3')) }}
        {{ Form::select('category_id', $categories, null, array('class' => 'form-control col', 'placeholder'=>__('Select Category...'))) }}
    </div>
    <div class="form-group row">
        {{ Form::label('attachment', __('Attachment'), array('class'=>'col-3')) }}
        {{ Form::file('attachment', array('class' => 'form-control col','accept'=>'.jpeg,.jpg,.png,.doc,.pdf')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('description', __('Description'), array('class'=>'col-3')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control col','rows'=>'2']) !!}
    </div>
    @if(\Auth::user()->type=='company')
    <div class="form-group row">
        {{ Form::label('user_id', __('Owner'), array('class'=>'col-3')) }}
        {{ Form::select('user_id', $owners, null, array('class' => 'form-control col')) }}
    </div>
    @endif
</div>
@include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Update'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
