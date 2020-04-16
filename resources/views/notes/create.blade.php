@extends('layouts.modal')

@section('form-start')
    {{Form::open(array('route' => array('notes.store'), 'data-remote' => 'true'))}}
@endsection

@section('title')
    {{__('Create Note')}}
@endsection

@section('content')
<div class="tab-content">
    {!! Form::hidden('lead_id', $lead_id) !!}
    <div class="form-group row align-items-center">
        {{Form::label('title',__('Title'), array('class'=>'col-3')) }}
        {{Form::text('title',null,array('class'=>'form-control col', 'placeholder'=>__('Followup Preparation')))}}
    </div>
    <div class="form-group row required">
        {{ Form::label('text', __('Text'), array('class'=>'col-3')) }}
        {!! Form::textarea('text', null,array('class' => 'form-control col','rows'=>'3', 'required'=>'required')) !!}
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

