
{{ Form::model($project, array('route' => array('projects.update', $project->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="form-group  col-md-6">
        {{ Form::label('name', __('Projects Name')) }}
        {{ Form::text('name', null, array('class' => 'form-control font-style','required'=>'required')) }}
        @error('name')
        <span class="invalid-name" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
    </span>
        @enderror
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('price', __('Projects Price')) }}
        {{ Form::number('price', null, array('class' => 'form-control','required'=>'required')) }}
        @error('price')
        <span class="invalid-price" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
    </span>
        @enderror
    </div>
    <div class="form-group  col-md-6">
        {{ Form::label('date', __('Due Date')) }}
        {{ Form::date('date', $project->due_date, array('class' => 'form-control','required'=>'required')) }}
        @error('date')
            <span class="invalid-date" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group  col-md-6">
        {{ Form::label('client', __('Client')) }}
        {!! Form::select('client', $clients, null,array('class' => 'form-control font-style','required'=>'required')) !!}
        @error('client')
        <span class="invalid-client" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group col-md-12">
        {{ Form::label('lead', __('Lead')) }}
        {!! Form::select('lead', $leads, null,array('class' => 'form-control font-style','required'=>'required')) !!}
        @error('lead')
        <span class="invalid-lead" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group  col-md-12">
        {{ Form::label('label', __('Label')) }}
        <div class="container-fluid">
            <div class="row">
                <div class="gutters-xs-1">
                    @foreach($labels as $label)
                        <div class="col-auto col-md-1 col-sm-1 col-xs-2">
                            <label class="colorinput">
                                <input name="label" type="radio" value="{{$label->id}}" {{($label->id==$project->label)?'checked':''}} class="colorinput-input">
                                <span class="colorinput-color {{$label->color}}"></span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('description', __('Description')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control font-style','rows'=>'2']) !!}
        @error('description')
        <span class="invalid-description" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
    </span>
        @enderror
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Update'),array('class'=>'btn blue'))}}
</div>
{{ Form::close() }}
