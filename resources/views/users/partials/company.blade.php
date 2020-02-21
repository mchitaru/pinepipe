{{Form::model($user->settings,array('route'=>'settings.company','method'=>'post', 'enctype' => 'multipart/form-data'))}}
<div class="card-body">
    <div class="row">
        <div class="media mb-4 avatar-container">
            <div class="d-flex flex-column avatar-preview">
                <img width="60" height="60" alt="{{$user->settings['company_name']}}" {!! empty($user->settings['company_logo']) ? "avatar='".$user->settings['company_name']."'" : "" !!} class="rounded" src="{{!empty($user->settings['company_logo'])?Storage::url($user->settings['company_logo']):""}}" data-filter-by="alt"/>
            </div>
            <div class="media-body ml-3">
                <div class="custom-file custom-file-naked d-block mb-1">
                    <input type="file" class="custom-file-input avatar-input d-none" name="company_logo" id="company_logo">
                    <label class="custom-file-label position-relative" for="company_logo">
                    <span class="btn btn-primary">
                        {{__('Upload logo')}}
                    </span>
                    </label>
                    <label class="file-label position-relative d-none"></label>
                </div>
                <div class="alert alert-warning text-small" role="alert">
                    <small>{{__('For best results, use an image at least 256px by 256px in either .jpg or .png format')}}</small>
                </div>            
            </div>
        </div>
        <!--end of logo-->

        <div class="form-group col-md-6">
            {{Form::label('company_name *',__('Company Name *')) }}
            {{Form::text('company_name',null,array('class'=>'form-control font-style'))}}
            @error('company_name')
            <span class="invalid-company_name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{Form::label('company_email',__('Company Email *')) }}
            {{Form::text('company_email',null,array('class'=>'form-control'))}}
            @error('company_email')
            <span class="invalid-company_email" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{Form::label('company_address',__('Address')) }}
            {{Form::text('company_address',null,array('class'=>'form-control font-style'))}}
            @error('company_address')
            <span class="invalid-company_address" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{Form::label('company_city',__('City')) }}
            {{Form::text('company_city',null,array('class'=>'form-control font-style'))}}
            @error('company_city')
            <span class="invalid-company_city" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{Form::label('company_state',__('State')) }}
            {{Form::text('company_state',null,array('class'=>'form-control font-style'))}}
            @error('company_state')
            <span class="invalid-company_state" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{Form::label('company_zipcode',__('Zip/Post Code')) }}
            {{Form::text('company_zipcode',null,array('class'=>'form-control'))}}
            @error('company_zipcode')
            <span class="invalid-company_zipcode" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group  col-md-6">
            {{Form::label('company_country',__('Country')) }}
            {{Form::text('company_country',null,array('class'=>'form-control font-style'))}}
            @error('company_country')
            <span class="invalid-company_country" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{Form::label('company_phone',__('Phone')) }}
            {{Form::text('company_phone',null,array('class'=>'form-control'))}}
            @error('company_phone')
            <span class="invalid-company_phone" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
</div>
<div class="row justify-content-end">
    {{Form::submit(__('Save Change'),array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}
