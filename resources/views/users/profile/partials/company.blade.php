@php
use App\CompanySettings;
@endphp

{{Form::model($user->companySettings, array('route'=>'settings.company','method'=>'post', 'enctype' => 'multipart/form-data'))}}
<div class="card-body">
    <div class="media mb-4 avatar-container">
        <div class="d-flex flex-column avatar-preview">
            <img width=60 height=60 alt="{{$companyName}}" {!! !$companyLogo ? "avatar='".$companyName."'" : "" !!} class="rounded" src="{{$companyLogo?$companyLogo->getFullUrl('thumb'):""}}" data-filter-by="alt"/>
        </div>
        <div class="media-body ml-3">
            <div class="custom-file custom-file-naked d-block mb-1">
                <input type="file" class="custom-file-input avatar-input d-none" name="logo" id="logo" accept="image/*">
                <label class="custom-file-label position-relative" for="logo">
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

    <div class="form-group row align-items-center required">
        {{Form::label('name',__('Company Name'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('name',null,array('class'=>'form-control font-style', 'placeholder'=>__('Pinepipe')))}}
            @error('name')
            <span class="invalid-company_name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('email',__('Company Email'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('email',null,array('class'=>'form-control', 'placeholder'=>__('team@pinepipe.com')))}}
            @error('email')
            <span class="invalid-company_email" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('address',__('Address'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('address',null,array('class'=>'form-control font-style', 'placeholder'=>__('101 California Street')))}}
            @error('address')
            <span class="invalid-company_address" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('city',__('City'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('city',null,array('class'=>'form-control font-style', 'placeholder'=>__('San Francisco')))}}
            @error('city')
            <span class="invalid-company_city" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('state',__('State'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('state',null,array('class'=>'form-control font-style', 'placeholder'=>__('California')))}}
            @error('state')
            <span class="invalid-company_state" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('zipcode',__('Zip/Post Code'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('zipcode',null,array('class'=>'form-control', 'placeholder'=>__('CA 94111')))}}
            @error('zipcode')
            <span class="invalid-company_zipcode" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('country',__('Country'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('country',null,array('class'=>'form-control font-style', 'placeholder'=>__('United States')))}}
            @error('country')
            <span class="invalid-company_country" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('phone',__('Phone'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('phone',null,array('class'=>'form-control', 'placeholder'=>__('(800) 613-1303')))}}
            @error('phone')
            <span class="invalid-company_phone" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('tax',__('TAX ID'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('tax',null, array('class'=>'form-control', 'placeholder'=>__('########')))}}
            @error('tax')
            <span class="invalid-tax" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('registration',__('Registration ID'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('registration',null, array('class'=>'form-control', 'placeholder'=>__('J##/####/##.##.####')))}}
            @error('registration')
            <span class="invalid-registration" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('bank',__('Bank'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('bank',null, array('class'=>'form-control', 'placeholder'=>'ING'))}}
            @error('bank')
            <span class="invalid-bank" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('iban',__('IBAN'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('iban',null, array('class'=>'form-control', 'placeholder'=>__('US##INGB################')))}}
            @error('iban')
            <span class="invalid-iban" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('invoice',__('Invoice Prefix'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('invoice',null, array('class'=>'form-control', 'placeholder'=>__('#INV')))}}
            @error('invoice')
            <span class="invalid-invoice_prefix" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('receipt',__('Receipt Prefix'), array('class'=>'col-3')) }}
        <div class="col">
            {{Form::text('receipt',null, array('class'=>'form-control', 'placeholder'=>__('#RPT')))}}
            @error('receipt')
            <span class="invalid-receipt_prefix" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{Form::label('currency',__('Currency'), array('class'=>'col-3')) }}
        <div class="col">
            {!! Form::select('currency', $currencies, ($user->companySettings == null) ? \Auth::user()->getDefaultCurrency() : null, array('class' => 'form-control col', 'lang'=>\Auth::user()->locale)) !!}
            @error('currency')
            <span class="invalid-site_currency" role="alert">
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
