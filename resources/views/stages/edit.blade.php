@extends('layouts.modal')

@section('form-start')
    {{ Form::model($stage, array('route' => array('stages.update', $stage->id), 'method' => 'PUT', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Edit Stage')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row align-items-center">
        {{Form::label('name',__('Name'), array('class'=>'col-3')) }}
        {{Form::text('name',null,array('class'=>'form-control col', 'placeholder'=>__('To Do')))}}
    </div>
    <input type="hidden" name="class" value="{{$class}}">
    <div class="form-group row align-items-center">
        <div class="col-3">
        </div>
        <div class="form-group col custom-control custom-checkbox custom-checkbox-switch">
            <input type="hidden" name="open" value="0">
            {{Form::checkbox('open', 1, null, ['class'=>'custom-control-input', 'id' =>'open'])}}
            {{Form::label('open', __('Open'), ['class'=>'custom-control-label'])}}
        </div>
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

