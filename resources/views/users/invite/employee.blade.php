@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('route' => array('users.invite.store'), 'method'=>'post', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Invite Employee')}}
@endsection

@section('content')
<div class="tab-content">
    {!! Form::hidden('role', 'employee') !!}
    <p>{{__('Invite an employee by email')}}</p>
    <div class="form-group align-items-center">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="material-icons">email</i>
          </span>
        </div>
        {{Form::email('email', null, array('class' => 'form-control col','required'=>'required', 'placeholder'=>__('team@pinepipe.com')))}}
      </div>
    </div>
    <div class="alert alert-warning text-small" role="alert">
      <span><p>{{__('Invite employees with extended access.')}}</p>
        <p>{{__('They can:')}}</p>
        <p>
          <ul class="good">
            <li>{{__('Manage company clients.')}}</li>
            <li>{{__('Create & manage leads.')}}</li>
            <li>{{__('Work on assigned projects & tasks.')}}</li>
            <li>{{__('Create new projects & tasks.')}}</li>
            <li>{{__('Create expenses & invoices.')}}</li>
            <li>{{__('Track worked time.')}}</li>
          </ul>        
        </p>                
        <p>{{__('They cannot:')}}</p>
        <p>
          <ul class="bad">
            <li>{{__('View or access projects & items on projects they are not assigned to.')}}</li>
          </ul>              
        </p>
  </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Send Invite'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
    {{ Form::close() }}
@endsection
