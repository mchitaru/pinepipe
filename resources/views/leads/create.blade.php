@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('url' => 'leads', 'method'=>'post', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Add New Lead')}}
@endsection

@section('content')
<div class="tab-content">
    <h6>{{__('General Details')}}</h6>
    <div class="form-group row align-items-center required">
        {{ Form::label('name', __('Name'), array('class'=>'col-3')) }}
        {{ Form::text('name', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>__('Design Project'))) }}
    </div>
    <div class="form-group row">
        {{ Form::label('price', __('Value'), array('class'=>'col-3')) }}
        {{ Form::number('price', '', array('class' => 'form-control col', 'placeholder'=>\Auth::user()->priceFormat(5000))) }}
    </div>
    <div class="form-group row">
        {{ Form::label('stage_id', __('Stage'), array('class'=>'col-3')) }}
        {{ Form::select('stage_id', $stages, $stage_id, array('class' => 'form-control col font-style selectric','required'=>'required')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('category_id', __('Source'), array('class'=>'col-3')) }}
        {!! Form::select('category_id', $categories, $category_id, array('class' => 'tags form-control col font-style selectric', 'placeholder'=>'...')) !!}
    </div>
    <hr>
    <h6>{{__('Attach')}}</h6>
    <div class="form-group row required">
        {{ Form::label('client_id', __('Client'), array('class'=>'col-3')) }}
        {!! Form::select('client_id', $clients, $client_id, array('class' => (Gate::check('create client')?'tags':'').' form-control col font-style selectric', 'required'=>'true', 'placeholder'=>'...',
                            'data-refresh'=>route('leads.refresh','0'))) !!}
    </div>
    <div class="form-group row">
        {{ Form::label('contact_id', __('Contact'), array('class'=>'col-3')) }}
        {!! Form::select('contact_id', $contacts, null, array('class' => (Gate::check('create contact')?'tags':'').' form-control col font-style selectric', 'placeholder'=>'...')) !!}
    </div>
    <hr>
    <h6>{{__('Visibility')}}</h6>
    <div class="row">
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-everyone" name="visibility" class="custom-control-input" disabled="true" >
        <label class="custom-control-label" for="visibility-everyone">{{__('Everyone')}}</label>
        </div>
    </div>
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-me" name="visibility" class="custom-control-input" disabled="true" checked>
        <label class="custom-control-label" for="visibility-me">{{__('Just me')}}</label>
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
