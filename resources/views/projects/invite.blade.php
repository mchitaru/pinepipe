@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('route' => array('projects.invite.store',$project->id))) }}
@endsection

@section('title')
    {{__('Invite Users')}}
@endsection

@section('content')
<div class="tab-content">
    <p>{{__('Send an invite link via email to add members to the project')}}</p>
    <div>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="material-icons">email</i>
          </span>
        </div>
        {{Form::email('email', null, array('class' => 'form-control col','required'=>'required', 'placeholder'=>__('Recipient email address')))}}
      </div>
      <div class="text-right text-small mt-2">
        <a href="#" role="button">{{__('Add another recipient')}}</a>
      </div>
    </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Invite'), array('class'=>'btn btn-primary disabled', 'disabled', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
    {{ Form::close() }}
@endsection
