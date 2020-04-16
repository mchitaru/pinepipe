{{Form::model($settings,array('route'=>'settings.system','method'=>'post'))}}
<div class="card-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{Form::label('site_currency',__('Currency')) }}
            {!! Form::select('site_currency', $currencies, $settings['site_currency'],array('class' => 'form-control col')) !!}            
            @error('site_currency')
            <span class="invalid-site_currency" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            {{Form::label('invoice_prefix',__('Invoice Prefix')) }}
            {{Form::text('invoice_prefix',null,array('class'=>'form-control'))}}
            @error('invoice_prefix')
            <span class="invalid-invoice_prefix" role="alert">
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
