@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('url' => 'expenses', 'enctype' => "multipart/form-data")) }}
@endsection

@section('title')
    {{__('Create Expense')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row required">
        {{ Form::label('amount', __('Amount'), array('class'=>'col-3')) }}
        {{ Form::number('amount', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>\Auth::user()->priceFormat(500))) }}
    </div>
    <div class="form-group row">
        {{ Form::label('date', __('Date'), array('class'=>'col-3')) }}
        {{ Form::text('date', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Select Date',
                                        'data-flatpickr', 'data-default-date'=> date('Y-m-d'), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('project_id', __('Project'), array('class'=>'col-3')) }}
        {{ Form::select('project_id', $projects, $project_id, array('class' => 'form-control col', 'placeholder'=>'Select Project...')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('category_id', __('Category'), array('class'=>'col-3')) }}
        {{ Form::select('category_id', $categories, null, array('class' => 'form-control col', 'placeholder'=>'Select Category...')) }}
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
        {{ Form::select('user_id', $owners, \Auth::user()->id, array('class' => 'form-control col')) }}
    </div>
    @endif
</div>
@include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Create'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
