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
            <label for="site_date_format" class="form-control-label">{{__('Date Format')}}</label>
            <select type="text" name="site_date_format" class="form-control selectric" id="site_date_format">
                <option value="M j, Y" @if(@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>Jan 1,2015</option>
                <option value="d-m-Y" @if(@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>d-m-y</option>
                <option value="m-d-Y" @if(@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>m-d-y</option>
                <option value="Y-m-d" @if(@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>y-m-d</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="site_time_format" class="form-control-label">{{__('Time Format')}}</label>
            <select type="text" name="site_time_format" class="form-control selectric" id="site_time_format">
                <option value="g:i A" @if(@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>10:30 PM</option>
                <option value="g:i a" @if(@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>10:30 pm</option>
                <option value="H:i" @if(@$settings['site_time_format'] == 'H:i') selected="selected" @endif>22:30</option>
            </select>
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
