@php
use App\Http\Helpers;
@endphp

{{Form::model($user, array('route' => array('profile.update', 'personal'), 'method' => 'put', 'enctype' => "multipart/form-data"))}}
<div class="media mb-4 avatar-container">
    <div class="d-flex flex-column avatar-preview">
        {!!Helpers::buildAvatar($user, 60, 'rounded')!!}
    </div>
    <div class="media-body ml-3">
        <div class="custom-file custom-file-naked d-block mb-1">
            <input type="file" class="custom-file-input avatar-input d-none" name="avatar" id="avatar">
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
<div class="row justify-content-end">
    @can('edit account')
        {{Form::submit('Save',array('class'=>'btn btn-primary'))}}
    @endcan
</div>
{{Form::close()}}
