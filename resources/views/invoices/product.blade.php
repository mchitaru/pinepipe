@extends('layouts.modal')

@section('form-start')
    {{ Form::model($invoice, array('route' => array('invoices.products.store', $invoice->id), 'method' => 'POST')) }}
@endsection

@section('title')
    {{__('Create Invoice')}}
@endsection

@section('content')
<div class="tab-content">
    <h6>{{__('Project Name')}}</h6>
    <div class="form-group row align-items-center">
        <input type="text" class="form-control col" value="{{$invoice->project->name}}" readonly>
    </div>
    <hr>
    <h6>{{__('What is Invoiced?')}}</h6>
    <div class="form-group row">
        <div class="col">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="customRadio5" name="type" value="milestone" checked="checked" onclick="hide_show(this)">
                <label class="custom-control-label" for="customRadio5">{{__('Milestone & Task')}}</label>
            </div>
        </div>
        <div class="col">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="customRadio6" name="type" value="other" onclick="hide_show(this)">
                <label class="custom-control-label" for="customRadio6">{{__('Other')}}</label>
            </div>
        </div>
    </div>

    <div id="milestone">
        <div class="form-group row">
            <label class="col-3" for="milestone_id">{{__('Milestone')}}</label>
            <select class="form-control col custom-select" onchange="getTask(this,{{$invoice->project_id}})" id="milestone_id" name="milestone_id">
                <option value="" selected="selected">{{__('Select Milestone')}}</option>
                @foreach($milestones as  $milestone)
                    <option value="{{$milestone->id}}">{{$milestone->title}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group row">
            <label class="col-3" for="task_id">{{__('Task')}}</label>
            <select class="form-control col custom-select" id="task_id" name="task_id">
                <option value="" selected="selected">{{__('Select Task')}}</option>
                @foreach($tasks as  $task)
                    <option value="{{$task->id}}">{{$task->title}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div id="other" style="display: none">
        <div id="milestone">
            <div class="form-group row">
                <label class="col-3" for="title">{{__('Product')}}</label>
                <input type="text" class="form-control col" name="title">
            </div>
        </div>
    </div>
    <hr>
    <h6>{{__('Pricing')}}</h6>
    <div class="form-group row">
        <label class="col-3" for="price">{{__('Price')}}</label>
        <input type="number" class="form-control col" name="price" required>
    </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
@if(isset($invoice))
    {{Form::submit(__('Save'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@else
{{Form::submit(__('Create'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endif
@endsection

@section('form-end')
{{ Form::close() }}
@endsection