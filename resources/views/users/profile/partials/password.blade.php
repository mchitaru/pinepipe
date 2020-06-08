{{Form::model($user,array('route' => array('profile.password',$user->handle()), 'method' => 'patch'))}}
<div class="form-group row align-items-center">
    {{Form::label('current_password',__('Current Password'), array('class'=>'col-3'))}}
    <div class="col">
        {{Form::password('current_password',array('class'=>'form-control','placeholder'=>__('Enter Current Password')))}}
        @error('current_password')
        <span class="invalid-current_password" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="form-group row align-items-center">
    {{Form::label('new_password',__('New Password'), array('class'=>'col-3'))}}
    <div class="col">
        {{Form::password('new_password',array('class'=>'form-control','placeholder'=>__('Enter New Password')))}}
        @error('new_password')
        <span class="invalid-new_password" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
<div class="form-group row align-items-center">
    {{Form::label('new_password_confirmation',__('Confirm Password'), array('class'=>'col-3'))}}
    <div class="col">
        {{Form::password('new_password_confirmation',array('class'=>'form-control','placeholder'=>__('Confirm Password')))}}
        @error('new_password_confirmation')
        <span class="invalid-confirm_password" role="alert">
        <strong class="text-danger">{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>

<div class="row justify-content-end">
    {{Form::submit(__('Change Password'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}
