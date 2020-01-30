@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('url' => 'leads')) }}
@endsection

@section('title')
    {{__('Add New Lead')}}
@endsection

@section('content')
<div class="tab-content">
    <h6>{{__('General Details')}}</h6>
    <div class="form-group row align-items-center required">
        {{ Form::label('name', __('Name'), array('class'=>'col-3')) }}
        {{ Form::text('name', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Lead name')) }}
    </div>
    <div class="form-group row required">
        {{ Form::label('price', __('Value'), array('class'=>'col-3')) }}
        {{ Form::number('price', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Lead Value')) }}
    </div>
    @if(\Auth::user()->type=='company')
        <div class="form-group row required">
            {{ Form::label('user_id', __('Assign To'), array('class'=>'col-3')) }}
            {!! Form::select('user_id', $owners, null,array('class' => 'form-control col font-style selectric','required'=>'required')) !!}
        </div>
    @endif
    <div class="form-group row">
        {{ Form::label('stage_id', __('Stage'), array('class'=>'col-3')) }}
        {{ Form::select('stage_id', $stages,null, array('class' => 'form-control col font-style selectric','required'=>'required')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('client_id', __('Client'), array('class'=>'col-3')) }}
        {!! Form::select('client_id', $clients, null,array('class' => 'form-control col font-style selectric','required'=>'required')) !!}
    </div>
    <div class="form-group row">
        {{ Form::label('source_id', __('Source'), array('class'=>'col-3')) }}
        {!! Form::select('source_id', $sources, null,array('class' => 'form-control col font-style selectric','required'=>'required')) !!}
    </div>
    <div class="form-group row">
        {{ Form::label('notes', __('Notes'), array('class'=>'col-3')) }}
        {!! Form::textarea('notes', '',array('class' => 'form-control col','rows'=>'3')) !!}
    </div>
    <hr>
    <h6>{{__('Visibility')}}</h6>
    <div class="row">
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-everyone" name="visibility" class="custom-control-input" disabled="true" >
        <label class="custom-control-label" for="visibility-everyone">Everyone</label>
        </div>
    </div>
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-me" name="visibility" class="custom-control-input" disabled="true" checked>
        <label class="custom-control-label" for="visibility-me">Just me</label>
        </div>
    </div>
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
