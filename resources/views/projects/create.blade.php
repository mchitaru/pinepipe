@extends('layouts.modal')

@section('form-start')
@if($is_create)
    {{ Form::open(array('url' => 'projects', 'data-remote' => 'true')) }}
@else
    {{ Form::model($project, array('route' => array('projects.update', $project->id), 'method' => 'PUT', 'data-remote' => 'true')) }}
@endif
@endsection

@section('title')
@if($is_create)
    {{__('Add New Project')}}
@else
    {{__('Edit Project')}}
@endif
@endsection

@section('content')
<ul class="nav nav-tabs nav-fill" role="tablist">
    <li class="nav-item">
    <a class="nav-link active" id="project-add-details-tab" data-toggle="tab" href="#project-add-details" role="tab" aria-controls="project-add-details" aria-selected="true">Details</a>
    </li>
    <li class="nav-item">
    <a class="nav-link" id="project-add-members-tab" data-toggle="tab" href="#project-add-members" role="tab" aria-controls="project-add-members" aria-selected="false">Members</a>
    </li>
</ul>
<div class="tab-content">
<div class="tab-pane fade show active" id="project-add-details" role="tabpanel">
    <h6>{{__('General Details')}}</h6>
    <div class="form-group row align-items-center">
        {{ Form::label('name', __('Project Name'), array('class'=>'col-3')) }}
        {{ Form::text('name', null, array('class' => 'form-control col', 'placeholder'=>'Project name', 'required'=>'required')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('description', __('Description'), array('class'=>'col-3')) }}
        {!!Form::textarea('description', null, ['class'=>'form-control col','rows'=>'3', 'placeholder'=>'Project description']) !!}
    </div>
    <div class="form-group row">
        {{ Form::label('client_id', __('Client'), array('class'=>'col-3')) }}
        {!! Form::select('client_id', $clients, null,array('class' => 'form-control col','required'=>'required')) !!}
    </div>
    <div class="form-group row">
        {{ Form::label('user_id', __('Assigned to'), array('class'=>'col-3')) }}
        {!! Form::select('user_id[]', $users, null,array('class' => 'form-control col','required'=>'required')) !!}
    </div>
@if(!$is_create)    
    <div class="form-group row">
        {{ Form::label('price', __('Budget'), array('class'=>'col-3')) }}
        {{ Form::number('price', null, array('class' => 'form-control col','required'=>'required')) }}
    </div>
    <div class="form-group row">
        {{ Form::label('lead_id', __('Lead'), array('class'=>'col-3')) }}
        {!! Form::select('lead_id', $leads, null,array('class' => 'form-control col','required'=>'required')) !!}
    </div>
@endif    
    <hr>
    <h6>{{__('Timeline')}}</h6>
    <div class="form-group row align-items-center">
        {{ Form::label('start_date', __('Start Date'), array('class'=>'col-3')) }}
        {{ Form::date('start_date', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Select Date', 'data-flatpickr', 'data-default-date'=> date('Y-m-d'), 'data-alt-input')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('due_date', __('Due Date'), array('class'=>'col-3')) }}
        {{ Form::date('due_date', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>'Select Date', 'data-flatpickr', 'data-default-date'=> date('Y-m-d'), 'data-alt-input')) }}
    </div>
    <div class="alert alert-warning text-small" role="alert">
    <span>{{__('You can change due dates at any time.')}}</span>
    </div>
    <hr>
    <h6>{{__('Visibility')}}</h6>
    <div class="row">
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-everyone" name="visibility" class="custom-control-input" checked>
        <label class="custom-control-label" for="visibility-everyone">Everyone</label>
        </div>
    </div>
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-members" name="visibility" class="custom-control-input">
        <label class="custom-control-label" for="visibility-members">Members</label>
        </div>
    </div>
    <div class="col">
        <div class="custom-control custom-radio">
        <input type="radio" id="visibility-me" name="visibility" class="custom-control-input">
        <label class="custom-control-label" for="visibility-me">Just me</label>
        </div>
    </div>
    </div>
</div>
<div class="tab-pane fade" id="project-add-members" role="tabpanel">
    <div class="users-manage" data-filter-list="form-group-users">
    <div class="mb-3">
        <ul class="avatars text-center">

        <li>
            <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar" data-toggle="tooltip" data-title="Claire Connors" />
        </li>

        <li>
            <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar" data-toggle="tooltip" data-title="Marcus Simmons" />
        </li>

        <li>
            <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar" data-toggle="tooltip" data-title="Peggy Brown" />
        </li>

        <li>
            <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar" data-toggle="tooltip" data-title="Harry Xai" />
        </li>

        </ul>
    </div>
    <div class="input-group input-group-round">
        <div class="input-group-prepend">
        <span class="input-group-text">
            <i class="material-icons">filter_list</i>
        </span>
        </div>
        <input type="search" class="form-control filter-list-input" placeholder="Filter members" aria-label="Filter Members">
    </div>
    <div class="form-group-users">

        <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="project-user-1" checked>
        <label class="custom-control-label" for="project-user-1">
            <span class="d-flex align-items-center">
            <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar mr-2" />
            <span class="h6 mb-0" data-filter-by="text">Claire Connors</span>
            </span>
        </label>
        </div>

        <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="project-user-2" checked>
        <label class="custom-control-label" for="project-user-2">
            <span class="d-flex align-items-center">
            <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar mr-2" />
            <span class="h6 mb-0" data-filter-by="text">Marcus Simmons</span>
            </span>
        </label>
        </div>

        <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="project-user-3" checked>
        <label class="custom-control-label" for="project-user-3">
            <span class="d-flex align-items-center">
            <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar mr-2" />
            <span class="h6 mb-0" data-filter-by="text">Peggy Brown</span>
            </span>
        </label>
        </div>

        <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="project-user-4" checked>
        <label class="custom-control-label" for="project-user-4">
            <span class="d-flex align-items-center">
            <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar mr-2" />
            <span class="h6 mb-0" data-filter-by="text">Harry Xai</span>
            </span>
        </label>
        </div>

        <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="project-user-5">
        <label class="custom-control-label" for="project-user-5">
            <span class="d-flex align-items-center">
            <img alt="Sally Harper" src="assets/img/avatar-female-3.jpg" class="avatar mr-2" />
            <span class="h6 mb-0" data-filter-by="text">Sally Harper</span>
            </span>
        </label>
        </div>

        <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="project-user-6">
        <label class="custom-control-label" for="project-user-6">
            <span class="d-flex align-items-center">
            <img alt="Ravi Singh" src="assets/img/avatar-male-3.jpg" class="avatar mr-2" />
            <span class="h6 mb-0" data-filter-by="text">Ravi Singh</span>
            </span>
        </label>
        </div>

        <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="project-user-7">
        <label class="custom-control-label" for="project-user-7">
            <span class="d-flex align-items-center">
            <img alt="Kristina Van Der Stroem" src="assets/img/avatar-female-4.jpg" class="avatar mr-2" />
            <span class="h6 mb-0" data-filter-by="text">Kristina Van Der Stroem</span>
            </span>
        </label>
        </div>

        <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="project-user-8">
        <label class="custom-control-label" for="project-user-8">
            <span class="d-flex align-items-center">
            <img alt="David Whittaker" src="assets/img/avatar-male-4.jpg" class="avatar mr-2" />
            <span class="h6 mb-0" data-filter-by="text">David Whittaker</span>
            </span>
        </label>
        </div>

        <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="project-user-9">
        <label class="custom-control-label" for="project-user-9">
            <span class="d-flex align-items-center">
            <img alt="Kerri-Anne Banks" src="assets/img/avatar-female-5.jpg" class="avatar mr-2" />
            <span class="h6 mb-0" data-filter-by="text">Kerri-Anne Banks</span>
            </span>
        </label>
        </div>

        <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="project-user-10">
        <label class="custom-control-label" for="project-user-10">
            <span class="d-flex align-items-center">
            <img alt="Masimba Sibanda" src="assets/img/avatar-male-5.jpg" class="avatar mr-2" />
            <span class="h6 mb-0" data-filter-by="text">Masimba Sibanda</span>
            </span>
        </label>
        </div>

        <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="project-user-11">
        <label class="custom-control-label" for="project-user-11">
            <span class="d-flex align-items-center">
            <img alt="Krishna Bajaj" src="assets/img/avatar-female-6.jpg" class="avatar mr-2" />
            <span class="h6 mb-0" data-filter-by="text">Krishna Bajaj</span>
            </span>
        </label>
        </div>

        <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="project-user-12">
        <label class="custom-control-label" for="project-user-12">
            <span class="d-flex align-items-center">
            <img alt="Kenny Tran" src="assets/img/avatar-male-6.jpg" class="avatar mr-2" />
            <span class="h6 mb-0" data-filter-by="text">Kenny Tran</span>
            </span>
        </label>
        </div>

    </div>
    </div>
</div>
</div>
@include('partials.errors')
@endsection

@section('footer')
{{Form::submit(($is_create?__('Create'):__('Update')),array('class'=>'btn btn-primary', 'data-disable-with' => 'Saving...'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
