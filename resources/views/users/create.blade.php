@extends('layouts.modal')

@section('form-start')
    {{Form::open(array('url'=>'users','method'=>'post'))}}
@endsection

@section('title')
    {{__('Create User')}}
@endsection

@section('content')
<div class="tab-content">
    <h6>{{__('Account type')}}</h6>
    @if(\Auth::user()->type != 'super admin')
        <div class="form-group row required">
            {{ Form::label('role', __('User Role'), array('class'=>'col-3')) }}
            {!! Form::select('role', $roles, $role?$role->id:null,array('class' => 'form-control col','required'=>'required',
                            'data-refresh'=>route('users.refresh'))) !!}
        </div>
        @if($role && $role->name == 'client')
        <div class="form-group row required">
            {{ Form::label('client_id', __('Client'), array('class'=>'col-3')) }}
            {!! Form::select('client_id', $clients, null, array('class' => 'form-control col','required'=>'required')) !!}
        </div>
        @endif
    @endif
    <hr>
    <h6>{{__('User details')}}</h6>
    <div class="form-group row required">
        {{Form::label('name',__('Name'), array('class'=>'col-3')) }}
        {{Form::text('name',null,array('class'=>'form-control col', 'placeholder'=>__('Enter User Name'),'required'=>'required'))}}
    </div>
    <div class="form-group row required">
        {{Form::label('email',__('Email'), array('class'=>'col-3'))}}
        {{Form::text('email',null,array('class'=>'form-control col', 'placeholder'=>__('Enter User Email'),'required'=>'required'))}}
    </div>
    <div class="form-group row required">
        {{Form::label('password',__('Password'), array('class'=>'col-3'))}}
        {{Form::password('password',array('class'=>'form-control col', 'placeholder'=>__('Enter User Password'),'required'=>'required','minlength'=>"6"))}}
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

