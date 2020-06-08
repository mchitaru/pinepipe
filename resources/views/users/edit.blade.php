@extends('layouts.modal')

@section('form-start')
    {{Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Edit User')}}
@endsection

@section('content')
<div class="tab-content">
    @if(\Auth::user()->type != 'super admin')
        <div class="form-group row required">
            {{ Form::label('role', __('User Role'), array('class'=>'col-3')) }}
            {!! Form::select('role', $roles, $role->id, array('class' => 'form-control col', 'required'=>'required', 'readonly',
                                'data-refresh'=>route('users.refresh', $user->id))) !!}
        </div>
        @if($role && $role->name == 'client')
        <div class="form-group row required">
            {{ Form::label('client_id', __('Client'), array('class'=>'col-3')) }}
            {!! Form::select('client_id', $clients, null, array('class' => 'form-control col','required'=>'required', 'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) !!}
        </div>
        @endif
    @endif
    <div class="form-group row required">
        {{Form::label('name',__('Name'), array('class'=>'col-3')) }}
        {{Form::text('name',null,array('class'=>'form-control col', 'placeholder'=>__('Enter User Name'),'required'=>'required'))}}
    </div>
    <div class="form-group row required">
        {{Form::label('email',__('Email'), array('class'=>'col-3'))}}
        {{Form::text('email',null,array('class'=>'form-control col', 'placeholder'=>__('Enter User Email'),'required'=>'required'))}}
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