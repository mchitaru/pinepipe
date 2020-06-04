
{{Form::model($user, array('route' => array('profile.update', \Auth::user()->handle()), 'method' => 'put', 'enctype' => "multipart/form-data"))}}
<div class="media mb-4 avatar-container">
    <div class="d-flex flex-column avatar-preview">
        {!!Helpers::buildUserAvatar($user, 60, 'rounded')!!}
    </div>
    <div class="media-body ml-3">
        <div class="custom-file custom-file-naked d-block mb-1">
            <input type="file" class="custom-file-input avatar-input d-none" name="avatar" id="avatar" accept="image/*">
            <label class="custom-file-label position-relative" for="avatar">
            <span class="btn btn-primary">
                {{__('Upload avatar')}}
            </span>
            </label>
            <label class="file-label position-relative d-none"></label>
        </div>
        <div class="alert alert-warning text-small" role="alert">
            <small>{{__('For best results, use an image at least 256px by 256px in either .jpg or .png format')}}</small>
        </div>
    </div>
</div>
<!--end of avatar-->
<div class="form-group row align-items-center">
    {{Form::label('name',__('Name'), array('class'=>'col-3'))}}
    <div class="col">
        {{Form::text('name',null,array('class'=>'form-control','placeholder'=>_('Enter User Name')))}}
        @error('name')
        <span class="invalid-name" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="form-group row align-items-center">
    {{Form::label('email',__('Email'), array('class'=>'col-3'))}}
    <div class="col">
        {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))}}
        @error('email')
        <span class="invalid-email" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-3">Bio</label>
    <div class="col">
        {!!Form::textarea('bio', null, ['class'=>'form-control col','rows'=>'4', 'placeholder'=>'Tell us a little about yourself']) !!}
        <small>{{__('This will be displayed on your public profile')}}</small>
    </div>
</div>
<hr>
<div class="form-group row align-items-center">
    {{Form::label('locale',__('Language'), array('class'=>'col-3')) }}
    <div class="col">
        {!! Form::select('locale', $locales, null, array('class' => 'form-control col')) !!}
        @error('locale')
        <span class="invalid-locale" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="form-group row align-items-center">
    {{Form::label('timezone',__('Timezone'), array('class'=>'col-3')) }}
    <div class="col">
        {{Form::text('timezone', null, array('class'=>'form-control', 'disabled'))}}
    </div>
</div>
<div class="form-group row align-items-center">
    {{Form::label('handle',__('Public Profile URL'), array('class'=>'input-group col-3')) }}
    <div class="input-group col">
        <div class="input-group-prepend">
            <span class="input-group-text dark" id="basic-addon3">{{url('/profile').'/'}}</span>
        </div>
        {{Form::text('handle', null, array('class'=>'form-control', 'aria-describedby' => 'basic-addon3'))}}
        @error('handle')
        <span class="invalid-handle" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="row justify-content-end">
    {{Form::submit('Save',array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}
