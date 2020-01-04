{{ Form::open(array('url' => 'projects')) }}
<div class="modal-header">
    <h5 class="modal-title"></h5>
    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
    <i class="material-icons">close</i>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('name', __('Project Name')) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
            @error('name')
            <span class="invalid-name" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('price', __('Project Price')) }}
            {{ Form::number('price', '', array('class' => 'form-control','required'=>'required')) }}
            @error('price')
            <span class="invalid-price" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('date', __('Start Date')) }}
            {{ Form::date('start_date', '', array('class' => 'form-control','required'=>'required')) }}
            @error('start_date')
            <span class="invalid-start_date" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('due_date', __('Due Date')) }}
            {{ Form::date('due_date', '', array('class' => 'form-control','required'=>'required')) }}
            @error('due_date')
            <span class="invalid-due_date" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('client', __('Client')) }}
            {!! Form::select('client', $clients, null,array('class' => 'form-control font-style','required'=>'required')) !!}
            @error('client')
            <span class="invalid-client" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('user', __('User')) }}
            {!! Form::select('user[]', $users, null,array('class' => 'form-control font-style','required'=>'required')) !!}
            @error('user')
            <span class="invalid-user" role="alert">
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
        <div class="form-group col-md-12">
            {{ Form::label('label', __('Label')) }}
            <div class="container-fluid">
                <div class="row">
                    @foreach($labels as $label)
                        <div class="col-auto col-xs-1">
                            <label class="colorinput">
                                <input name="label" type="radio" value="{{$label->id}}" class="colorinput-input">
                                <span class="colorinput-color {{$label->color}}"></span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description')) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'2']) !!}
            @error('description')
            <span class="invalid-description" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
            @enderror
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
</div>
{{ Form::close() }}
