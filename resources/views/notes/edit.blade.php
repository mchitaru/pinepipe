@extends('layouts.modal')

@section('form-start')
    {{ Form::model($note, array('route' => array('notes.update', $note->id), 'method' => 'PUT', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Edit Note')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group required">
        {!! Form::textarea('text', null,array('class' => 'form-control','rows'=>'6', 'required'=>'required')) !!}
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

