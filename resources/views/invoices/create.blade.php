@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('url' => 'invoices', 'data-remote' => 'true')) }}
@endsection

@section('title')
    {{__('Create Invoice')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row align-items-center required">
        {{ Form::label('project_id', __('Project'), array('class'=>'col-3')) }}
        {{ Form::select('project_id', $projects, $project_id, array('class' => 'form-control col', 'required'=>'required', 'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) }}
    </div>
    <div class="form-group row">
        {{ Form::label('issue_date', __('Issue Date'), array('class'=>'col-3')) }}
        {{ Form::date('issue_date', null, array('class' => 'start form-control col','required'=>'required', 'placeholder'=>'...',
                                            'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-default-date'=> date('Y-m-d'), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('due_date', __('Due Date'), array('class'=>'col-3')) }}
        {{ Form::date('due_date', null, array('class' => 'end form-control col','required'=>'required', 'placeholder'=>'...', 
                                            'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-default-date'=> date('Y-m-d', strtotime("+1 months", strtotime(date("Y-m-d")))), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row">
        {{Form::label('locale',__('Language'), array('class'=>'col-3')) }}
        {!! Form::select('locale', $locales, $_user->locale, array('class' => 'form-control col', 'lang'=>\Auth::user()->locale)) !!}
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