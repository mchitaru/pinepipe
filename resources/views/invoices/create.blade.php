@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('url' => 'invoices')) }}
@endsection

@section('title')
    {{__('Create Invoice')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row align-items-center required">
        {{ Form::label('project_id', __('Project'), array('class'=>'col-3')) }}
        {{ Form::select('project_id', $projects, $project_id, array('class' => 'form-control col', 'required'=>'required', 'placeholder'=>__('Select Project...'))) }}
    </div>
    <div class="form-group row">
        {{ Form::label('issue_date', __('Issue Date'), array('class'=>'col-3')) }}
        {{ Form::date('issue_date', null, array('class' => 'start form-control col','required'=>'required', 'placeholder'=>__('Select Date...'),
                                            'data-flatpickr', 'data-default-date'=> date('Y-m-d'), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('due_date', __('Due Date'), array('class'=>'col-3')) }}
        {{ Form::date('due_date', null, array('class' => 'end form-control col','required'=>'required', 'placeholder'=>__('Select Date...'), 
                                            'data-flatpickr', 'data-default-date'=> date('Y-m-d', strtotime("+1 months", strtotime(date("Y-m-d")))), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('tax_id', __('Tax %'), array('class'=>'col-3')) }}
        {{ Form::select('tax_id', $taxes,null, array('class' => 'form-control col', 'placeholder'=>'No Tax')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('notes', __('Notes'), array('class'=>'col-3')) }}
        {!! Form::textarea('notes', null, ['class'=>'form-control col','rows'=>'3']) !!}
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