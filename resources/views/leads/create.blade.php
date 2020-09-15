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
    <div class="form-group row align-items-center">
        {{ Form::label('price', __('Value'), array('class'=>'col-3')) }}
        <div class="input-group col p-0">
            <div class="input-group-prepend">
                <span class="input-group-text">{{Helpers::getCurrencySymbol(\Auth::user()->getCurrency())}}</span>
            </div>
            {{ Form::number('price', '', array('class' => 'form-control col', 'placeholder'=>5000)) }}
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('stage_id', __('Stage'), array('class'=>'col-3')) }}
        {{ Form::select('stage_id', $stages, $stage_id, array('class' => 'form-control col font-style selectric','required'=>'required', 'lang'=>\Auth::user()->locale)) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('category_id', __('Source'), array('class'=>'col-3')) }}
        {!! Form::select('category_id', $categories, $category_id, array('class' => 'tags form-control col font-style selectric', 'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) !!}
    </div>
    <hr>
    <h6>{{__('Attach')}}</h6>
    <div class="form-group row align-items-center required">
        {{ Form::label('client_id', __('Client'), array('class'=>'col-3')) }}
        {!! Form::select('client_id', $clients, $client_id, array('class' => (Gate::check('create client')?'tags':'').' form-control col font-style selectric', empty($client_id)?'':'disabled', 'required'=>'true', 'placeholder'=>'...',
                            'data-refresh'=>route('leads.refresh','0'), 'lang'=>\Auth::user()->locale)) !!}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('contact_id', __('Contact'), array('class'=>'col-3')) }}
        {!! Form::select('contact_id', $contacts, null, array('class' => (Gate::check('create contact')?'tags':'').' form-control col font-style selectric', 'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) !!}
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
