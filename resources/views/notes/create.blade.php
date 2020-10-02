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
    {!! Form::hidden('project_id', $project_id) !!}
    <div class="form-group required">
        {!! Form::textarea('text', null,array('class' => 'form-control','rows'=>'3', 'required'=>'required')) !!}
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

