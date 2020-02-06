@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('route' => array('languages.store'))) }}
@endsection

@section('title')
    {{__('Add New Language')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('code', __('Language Code')) }}
            {{ Form::text('code', '', array('class' => 'form-control','required'=>'required')) }}
            @error('code')
            <span class="invalid-code" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
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


